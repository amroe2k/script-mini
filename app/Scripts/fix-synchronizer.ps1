# =============================================================
#  Fix Synchronizer eRapor - Patch Installer
#  Script  : fix-synchronizer.ps1
#  Tujuan  : Download dataweb.zip & jalankan fix-deploy.ps1
#            dari script.sekolahan.web.id
#  Folder  : C:\synchronizer\dataweb
# =============================================================
#  CARA PENGGUNAAN:
#    PowerShell : .\fix-synchronizer.ps1
#    CMD        : powershell -ExecutionPolicy Bypass -File "fix-synchronizer.ps1"
# =============================================================

#Requires -Version 5.1

# ── Konfigurasi ──────────────────────────────────────────────
$BaseUrl     = "https://script.sekolahan.web.id/patch-synchronizer-erapor"
$ZipUrl      = "$BaseUrl/dataweb.zip"
$ScriptUrl   = "$BaseUrl/fix-deploy.ps1"
$TargetDir   = "C:\synchronizer\dataweb"
$ZipFile     = "$TargetDir\dataweb.zip"
$TempScript  = "$env:TEMP\fix-deploy.ps1"

# ── Helper: Warna output ──────────────────────────────────────
function Write-Step  { param($msg) Write-Host "`n[>>] $msg" -ForegroundColor Cyan }
function Write-Ok    { param($msg) Write-Host "[OK] $msg"   -ForegroundColor Green }
function Write-Fail  { param($msg) Write-Host "[!!] $msg"   -ForegroundColor Red; throw "Proses dihentikan." }
function Write-Info  { param($msg) Write-Host "     $msg"   -ForegroundColor Gray }

# ── Header ────────────────────────────────────────────────────
Clear-Host
Write-Host "=============================================================" -ForegroundColor Blue
Write-Host "   Fix Synchronizer eRapor - Patch Installer" -ForegroundColor White
Write-Host "   sumber : script.sekolahan.web.id" -ForegroundColor Gray
Write-Host "=============================================================" -ForegroundColor Blue

# ── Langkah 1: Pastikan folder tujuan ada ─────────────────────
Write-Step "Menyiapkan folder tujuan: $TargetDir"
try {
    if (-not (Test-Path $TargetDir)) {
        New-Item -ItemType Directory -Path $TargetDir -Force | Out-Null
        Write-Ok "Folder berhasil dibuat: $TargetDir"
    } else {
        Write-Info "Folder sudah ada, melanjutkan..."
    }
} catch {
    Write-Fail "Gagal membuat folder '$TargetDir'. Jalankan script sebagai Administrator."
}

# ── Langkah 2: Download dataweb.zip ───────────────────────────
Write-Step "Mengunduh dataweb.zip dari server..."
Write-Info "URL : $ZipUrl"
Write-Info "Ke  : $ZipFile"

try {
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
    $ProgressPreference = 'SilentlyContinue'   # percepat download
    Invoke-WebRequest -Uri $ZipUrl -OutFile $ZipFile -UseBasicParsing -ErrorAction Stop
    $sizeMB = [math]::Round((Get-Item $ZipFile).Length / 1MB, 2)
    Write-Ok "dataweb.zip berhasil diunduh ($sizeMB MB)"
} catch {
    Write-Fail "Gagal mengunduh dataweb.zip: $_"
}

# ── Langkah 3: Ekstrak dataweb.zip ────────────────────────────
Write-Step "Mengekstrak dataweb.zip ke $TargetDir..."
try {
    # Hapus file lama sebelum ekstrak untuk menghindari konflik
    Get-ChildItem -Path $TargetDir -Exclude "dataweb.zip" | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
    Expand-Archive -Path $ZipFile -DestinationPath $TargetDir -Force -ErrorAction Stop
    Write-Ok "Ekstrak selesai."
} catch {
    Write-Fail "Gagal mengekstrak dataweb.zip: $_"
}

# ── Langkah 4: Download & jalankan fix-deploy.ps1 ─────────────
Write-Step "Mengunduh fix-deploy.ps1 dari server..."
Write-Info "URL : $ScriptUrl"

try {
    Invoke-WebRequest -Uri $ScriptUrl -OutFile $TempScript -UseBasicParsing -ErrorAction Stop
    Write-Ok "fix-deploy.ps1 berhasil diunduh ke temp."
} catch {
    Write-Fail "Gagal mengunduh fix-deploy.ps1: $_"
}

Write-Step "Menjalankan fix-deploy.ps1..."
try {
    # Jalankan dengan working directory di folder target
    Push-Location $TargetDir
    & powershell.exe -ExecutionPolicy Bypass -File $TempScript
    Pop-Location
    Write-Ok "fix-deploy.ps1 selesai dijalankan."
} catch {
    Pop-Location
    Write-Fail "Gagal menjalankan fix-deploy.ps1: $_"
}

# ── Selesai ───────────────────────────────────────────────────
Write-Host ""
Write-Host "=============================================================" -ForegroundColor Blue
Write-Host "   Proses selesai! Folder target: $TargetDir" -ForegroundColor Green
Write-Host "=============================================================" -ForegroundColor Blue
Write-Host ""
