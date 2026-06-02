# ============================================================
#  deploy-ssh.ps1 - Full Auto Deploy ke Shared Hosting
#  Framework : CodeIgniter 4 + SQLite3
#
#  Cara pakai:
#    .\deploy-ssh.ps1                  -> build + upload + extract + migrate
#    .\deploy-ssh.ps1 -SkipBuild       -> skip build (pakai ZIP yang ada)
#    .\deploy-ssh.ps1 -SkipMigrate     -> skip php spark migrate
#    .\deploy-ssh.ps1 -OverwriteDB     -> timpa database server dengan lokal
#
#  Tidak butuh file .sh di server!
#  Semua perintah dijalankan via SSH inline dari PowerShell.
# ============================================================

param (
    [switch]$SkipBuild,
    [switch]$SkipMigrate,
    [switch]$OverwriteDB,
    [string]$OutFile = "script-deploy.zip"
)

$ErrorActionPreference = "Stop"
$ProjectDir = $PSScriptRoot

# ── Konfigurasi SSH ───────────────────────────────────────────
$SshUser   = "sekolahan.web.id"
$SshHost   = "ssh.us.stackcp.com"
$SshTarget = "$SshUser@$SshHost"       # user@host untuk SCP & SSH
$RemoteDir = "~/sekolahan.web.id/script"  # Folder tujuan di server
$RemoteZip = "$RemoteDir/script-deploy.zip"
# Gunakan 'bash -l' agar PATH login shell termuat (mendapatkan PHP 8.3)
# Hosting ini tidak punya binary php8.3 langsung, hanya via bash -l
$UseLoginShell = $true                 # Set $false jika server punya binary php8.3 langsung
# ─────────────────────────────────────────────────────────────

function Log-Step { param($n,$msg); Write-Host "`n  [$n] $msg" -ForegroundColor Cyan }
function Log-OK   { param($msg);    Write-Host "      OK  $msg" -ForegroundColor Green }
function Log-Warn { param($msg);    Write-Host "      !!  $msg" -ForegroundColor Yellow }
function Log-Info { param($msg);    Write-Host "          $msg" -ForegroundColor DarkGray }
function Log-Err  {
    param($msg)
    Write-Host "`n  [ERROR] $msg`n" -ForegroundColor Red
    exit 1
}
function Run-SSH  {
    param($cmd, [string]$desc = "")
    if ($desc) { Log-Info $desc }
    $result = ssh $SshTarget $cmd
    if ($LASTEXITCODE -ne 0) { Log-Err "SSH command gagal: $cmd" }
    return $result
}

Write-Host ""
Write-Host "  =============================================" -ForegroundColor Magenta
Write-Host "    Script Collection - SSH Auto Deploy        " -ForegroundColor Magenta
Write-Host "    Host : $SshTarget" -ForegroundColor Magenta
Write-Host "  =============================================" -ForegroundColor Magenta
Write-Host ""

# ── [1/4] Build ────────────────────────────────────────────────
if (-not $SkipBuild) {
    Log-Step "1/4" "Build package..."
    $buildArgs = @{}
    if (-not $OverwriteDB) { $buildArgs['NoDB'] = $true }
    $buildArgs['OutFile'] = $OutFile

    & "$ProjectDir\build-deploy.ps1" @buildArgs
    if ($LASTEXITCODE -ne 0) { Log-Err "build-deploy.ps1 gagal" }
} else {
    Log-Step "1/4" "Skip build (-SkipBuild aktif)"
    Log-Warn "Menggunakan ZIP yang sudah ada: $OutFile"
}

$ZipPath = Join-Path $ProjectDir $OutFile
if (-not (Test-Path $ZipPath)) { Log-Err "File ZIP tidak ditemukan: $ZipPath" }
$ZipSize = [math]::Round((Get-Item $ZipPath).Length / 1KB, 1)
Log-OK "ZIP siap: $OutFile ($ZipSize KB)"

# ── [2/4] Upload via SCP ───────────────────────────────────────
Log-Step "2/4" "Upload ZIP ke server..."
Log-Info "Tujuan : $SshTarget`:$RemoteZip"

scp $ZipPath "${SshTarget}:${RemoteZip}"
if ($LASTEXITCODE -ne 0) { Log-Err "SCP upload gagal" }
Log-OK "Upload selesai ($ZipSize KB)"

# ── [3/4] Extract via SSH (tanpa .env & database) ─────────────
Log-Step "3/4" "Extract di server..."

# Tentukan apakah skip DB atau tidak
if (-not $OverwriteDB) {
    # Exclude .env, database, dan file .htaccess (root & public)
    $excludeFlags = "'.env' '*writable?database?*' '*public?.htaccess' '.htaccess'"
    Log-Info "Mode Aman (Default): database & .htaccess TIDAK ditimpa"
} else {
    # Exclude .env dan file .htaccess (root & public)
    $excludeFlags = "'.env' '*public?.htaccess' '.htaccess'"
    Log-Warn "Mode OverwriteDB: database server akan diupdate/ditimpa dari lokal (kecuali .htaccess)"
}

$extractCmd = "cd $RemoteDir && unzip -o script-deploy.zip -x $excludeFlags 2>&1 | tail -5"
$output = Run-SSH $extractCmd "Mengekstrak ZIP..."
Write-Host "          $output" -ForegroundColor DarkGray
Log-OK "Ekstrak selesai"

# Set permission writable/
Run-SSH "chmod -R 755 $RemoteDir/writable/ && mkdir -p $RemoteDir/writable/cache $RemoteDir/writable/logs $RemoteDir/writable/session" "Set permission writable/"
Log-OK "Permission writable/ selesai"

# Set permission public/
Run-SSH "chmod -R 755 $RemoteDir/public/" "Set permission public/"
Log-OK "Permission public/ selesai"

# Hapus ZIP dari server setelah ekstrak
Run-SSH "rm -f $RemoteZip" "Bersihkan ZIP dari server..."
Log-OK "ZIP server dihapus"

# ── [4/4] Migrate via SSH ──────────────────────────────────────
if (-not $SkipMigrate) {
    Log-Step "4/4" "Jalankan php spark migrate..."
    
    $phpVer = Run-SSH "bash -l -c 'php -v 2>&1 | head -1'" "Cek versi PHP di server..."
    Log-Info "PHP: $phpVer"
    
    if (-not ($phpVer -match '8\.[23]\.')) {
        Log-Warn "PHP yang aktif bukan 8.2/8.3 — migrate mungkin gagal!"
        Log-Warn "Versi terdeteksi: $phpVer"
    }

    $migrateCmd = "bash -l -c 'cd $RemoteDir && php spark migrate --all 2>&1'"
    $migrateOut = Run-SSH $migrateCmd "Menjalankan migrasi..."
    Write-Host "          $migrateOut" -ForegroundColor DarkGray
    Log-OK "Migrasi selesai"
} else {
    Log-Step "4/4" "Skip migrasi (-SkipMigrate aktif)"
}

# ── Done ───────────────────────────────────────────────────────
Write-Host ""
Write-Host "  =============================================" -ForegroundColor Green
Write-Host "    Deploy selesai!                           " -ForegroundColor Green
Write-Host "    URL : https://script.sekolahan.web.id/    " -ForegroundColor Green
Write-Host "  =============================================" -ForegroundColor Green
Write-Host ""
