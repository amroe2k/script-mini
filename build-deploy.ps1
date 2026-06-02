# ============================================================
#  build-deploy.ps1 - Script Collection Deploy Packager
#  Framework : CodeIgniter 4 (PHP 8.2+) + SQLite3
#
#  Cara pakai:
#    .\build-deploy.ps1              -> build + paket
#    .\build-deploy.ps1 -SkipVendor -> skip composer install
#    .\build-deploy.ps1 -NoDB       -> tanpa database SQLite
#    .\build-deploy.ps1 -OutFile "release-v2.zip"
#
#  Output: script-deploy.zip (siap upload ke VPS / Shared Hosting PHP)
# ============================================================

param (
    [switch]$SkipVendor,
    [switch]$NoDB,
    [string]$OutFile = "script-deploy.zip"
)

$ErrorActionPreference = "Stop"
$ProjectDir = $PSScriptRoot
$OutZip     = Join-Path $ProjectDir $OutFile
$TempDir    = Join-Path $ProjectDir ".deploy-temp"

function Log-Step { param($n, $msg); Write-Host "`n  [$n] $msg" -ForegroundColor Cyan }
function Log-OK   { param($msg);     Write-Host "      OK  $msg" -ForegroundColor Green }
function Log-Warn { param($msg);     Write-Host "      !!  $msg" -ForegroundColor Yellow }
function Log-Info { param($msg);     Write-Host "          $msg" -ForegroundColor DarkGray }
function Log-Err  {
    param($msg)
    Write-Host "`n  [ERROR] $msg" -ForegroundColor Red
    exit 1
}

# ---- Banner ------------------------------------------------
Write-Host ""
Write-Host "  =============================================" -ForegroundColor DarkCyan
Write-Host "    Script Collection - Build & Deploy Packager" -ForegroundColor Cyan
Write-Host "    Framework : CodeIgniter 4 + SQLite3        " -ForegroundColor DarkGray
Write-Host "  =============================================" -ForegroundColor DarkCyan
Write-Host ""

# ---- [PRE] Cek PHP dan Composer ----------------------------
Log-Step "PRE" "Memeriksa environment PHP..."

$phpCheck = Get-Command php -ErrorAction SilentlyContinue
if (-not $phpCheck) { Log-Err "PHP tidak ditemukan di PATH. Install PHP 8.2+ dan tambahkan ke PATH." }
$phpVer = (php -r "echo PHP_VERSION;")
Log-OK "PHP $phpVer ditemukan"

$composerCheck = Get-Command composer -ErrorAction SilentlyContinue
if (-not $composerCheck) { Log-Err "Composer tidak ditemukan. Install dari https://getcomposer.org" }
# Suspend Stop agar stderr dari composer (info PHP version) tidak dianggap error
$_eap = $ErrorActionPreference; $ErrorActionPreference = "SilentlyContinue"
$composerVer = (composer --version 2>$null) -join "" -replace "\r|\n",""
$ErrorActionPreference = $_eap
if (-not $composerVer) { $composerVer = "Composer" }
Log-OK "$composerVer ditemukan"

# ---- [1/5] Composer Install --------------------------------
if ($SkipVendor) {
    Log-Step "1/5" "Skip composer install (-SkipVendor aktif)"
    if (-Not (Test-Path (Join-Path $ProjectDir "vendor"))) {
        Log-Err "Folder vendor/ tidak ditemukan. Jalankan tanpa -SkipVendor terlebih dahulu."
    }
    Log-Warn "Menggunakan vendor/ yang sudah ada"
} else {
    Log-Step "1/5" "Menjalankan composer install --no-dev..."
    Set-Location $ProjectDir
    composer install --no-dev --optimize-autoloader --no-interaction --no-progress
    if ($LASTEXITCODE -ne 0) { Log-Err "composer install gagal. Periksa error di atas." }
    if (-Not (Test-Path (Join-Path $ProjectDir "vendor"))) {
        Log-Err "Folder vendor/ tidak ada setelah composer install."
    }
    Log-OK "Dependensi PHP berhasil diinstall -> vendor/"
}

# ---- [2/5] Siapkan temp directory --------------------------
Log-Step "2/5" "Menyiapkan paket deploy..."
if (Test-Path $TempDir) { Remove-Item $TempDir -Recurse -Force }
New-Item -ItemType Directory -Path $TempDir | Out-Null

# Folder-folder CI4 yang wajib disalin
$foldersToCopy = @("app", "public", "vendor", "writable")
foreach ($folder in $foldersToCopy) {
    $src = Join-Path $ProjectDir $folder
    $dst = Join-Path $TempDir $folder
    if (Test-Path $src) {
        Copy-Item -Path $src -Destination $dst -Recurse
        Log-OK "$folder/ disalin"
    } else {
        Log-Warn "$folder/ tidak ditemukan - dilewati"
    }
}

# Bersihkan writable/ dari log/cache dev (biarkan struktur folder saja)
$writableClean = @("writable\cache\*", "writable\logs\*", "writable\session\*", "writable\debugbar\*")
foreach ($pattern in $writableClean) {
    $cleanPath = Join-Path $TempDir $pattern
    Remove-Item $cleanPath -Recurse -Force -ErrorAction SilentlyContinue
}
Log-OK "writable/ dibersihkan (cache/log/session dev dihapus)"

# File-file root CI4
$filesToCopy = @("spark", "composer.json", "composer.lock")
foreach ($file in $filesToCopy) {
    $src = Join-Path $ProjectDir $file
    if (Test-Path $src) {
        Copy-Item $src -Destination (Join-Path $TempDir $file)
        Log-OK "$file disalin"
    }
}

# Salin app/Scripts/ jika ada (PS1 scripts yang dihosting)
$scriptsDir = Join-Path $ProjectDir "app\Scripts"
if (Test-Path $scriptsDir) {
    Copy-Item -Path $scriptsDir -Destination (Join-Path $TempDir "app\Scripts") -Recurse -Force
    Log-OK "app/Scripts/ disalin (hosted PS1 scripts)"
}

# ---- Buat root .htaccess (redirect ke public/ untuk shared hosting) ----
# Dibutuhkan jika document root hosting mengarah ke root app, bukan ke public/
$rootHtaccess = @(
    "# Script Collection - Root .htaccess",
    "# Redirect semua request ke folder public/ (CodeIgniter 4 entry point)",
    "# Diperlukan jika document root hosting tidak bisa diubah ke public/",
    "",
    "<IfModule mod_rewrite.c>",
    "    RewriteEngine On",
    "",
    "    # Jangan redirect jika sudah di dalam public/",
    "    RewriteCond %{REQUEST_URI} !^/public/",
    "",
    "    # Redirect semua request ke public/",
    "    RewriteRule ^(.*)$ public/$1 [L]",
    "</IfModule>",
    "",
    "# Sembunyikan direktori sensitif dari akses langsung",
    "<IfModule mod_authz_core_module>",
    "    <FilesMatch `"^(\.env|composer\.(json|lock)|spark)$`">",
    "        Require all denied",
    "    </FilesMatch>",
    "</IfModule>"
)
$rootHtaccess -join "`n" | Set-Content (Join-Path $TempDir ".htaccess") -Encoding UTF8
Log-OK ".htaccess (root) dibuat -> redirect ke public/"

# ---- Database SQLite (opsional) ----------------------------
# Database disimpan di writable/database/scripts.db (sesuai WRITEPATH di Database.php)
if (-Not $NoDB) {
    $DbFile = Join-Path $ProjectDir "writable\database\scripts.db"
    if (Test-Path $DbFile) {
        $TempDbDir = Join-Path $TempDir "writable\database"
        if (-Not (Test-Path $TempDbDir)) { New-Item -ItemType Directory -Path $TempDbDir -Force | Out-Null }
        Copy-Item $DbFile -Destination (Join-Path $TempDbDir "scripts.db")
        Log-OK "writable/database/scripts.db disalin"
    } else {
        Log-Warn "writable/database/scripts.db tidak ditemukan - dilewati (akan auto-create saat migrasi)"
    }
} else {
    Log-Warn "-NoDB aktif: database dilewati"
}

# ---- Buat .env.example (format CI4) -----------------------
$envLines = @(
    "#--------------------------------------------------------------------",
    "# Script Collection - Environment Configuration",
    "# CodeIgniter 4 + SQLite3",
    "#",
    "# Salin file ini menjadi .env lalu sesuaikan nilainya:",
    "#   cp .env.example .env",
    "#--------------------------------------------------------------------",
    "",
    "#--------------------------------------------------------------------",
    "# ENVIRONMENT",
    "# Ubah ke 'production' saat deploy ke server live",
    "#--------------------------------------------------------------------",
    "CI_ENVIRONMENT = production",
    "",
    "#--------------------------------------------------------------------",
    "# APP",
    "# Sesuaikan baseURL dengan domain Anda",
    "#--------------------------------------------------------------------",
    "app.baseURL = 'https://domain-anda.com/'",
    "# app.forceGlobalSecureRequests = false",
    "",
    "#--------------------------------------------------------------------",
    "# DATABASE (SQLite3 - tidak perlu konfigurasi tambahan)",
    "# Path database dikontrol oleh WRITEPATH di app/Config/Database.php",
    "# Default: writable/database/scripts.db (JANGAN dioverride di sini)",
    "#--------------------------------------------------------------------",
    "database.default.DBDriver = 'SQLite3'",
    "",
    "#--------------------------------------------------------------------",
    "# SESSION",
    "#--------------------------------------------------------------------",
    "# session.driver = 'CodeIgniter\Session\Handlers\FileHandler'",
    "# session.savePath = 'writable/session'"
)
$envLines -join "`n" | Set-Content (Join-Path $TempDir ".env.example") -Encoding UTF8
Log-OK ".env.example dibuat (format CI4)"

# ---- Buat deploy.sh (Linux / cPanel) -----------------------
$deployShLines = @(
    "#!/usr/bin/env bash",
    "# deploy.sh - Script Collection Setup Script (Linux / cPanel)",
    "# Jalankan sekali setelah upload dan ekstrak ZIP:",
    "#   bash deploy.sh",
    "",
    "set -e",
    "",
    'SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"',
    'cd "$SCRIPT_DIR"',
    "",
    'echo ""',
    'echo "  ============================================="',
    'echo "    Script Collection - Deploy Setup          "',
    'echo "  ============================================="',
    'echo ""',
    "",
    "# [1] Buat .env dari .env.example jika belum ada",
    "if [ ! -f .env ]; then",
    "  cp .env.example .env",
    '  echo "  [>>] .env dibuat dari .env.example"',
    '  echo "       Sesuaikan app.baseURL di file .env sebelum lanjut!"',
    "fi",
    "",
    "# [2] Set permission folder writable/",
    'echo "  [>>] Set permission writable/..."',
    "chmod -R 755 writable/",
    "mkdir -p writable/cache writable/logs writable/session writable/debugbar",
    'echo "  [OK] Permission writable/ selesai"',
    "",
    "# [3] Set permission public/",
    "chmod -R 755 public/",
    'echo "  [OK] Permission public/ selesai"',
    "",
    "# [4] Jalankan migrasi database",
    'echo "  [>>] Menjalankan migrasi database..."',
    "php spark migrate --all",
    'echo "  [OK] Migrasi selesai"',
    "",
    'echo ""',
    'echo "  ============================================="',
    'echo "    Setup selesai! Konfigurasi web server:"  ',
    'echo "    Document Root -> /path/ke/app/public/    "',
    'echo "  ============================================="',
    'echo ""'
)
$deployShContent = $deployShLines -join "`n"
# Tulis tanpa BOM agar kompatibel dengan Linux (PowerShell Set-Content UTF8 menambahkan BOM)
[System.IO.File]::WriteAllText(
    (Join-Path $TempDir "deploy.sh"),
    $deployShContent,
    [System.Text.UTF8Encoding]::new($false)   # $false = tanpa BOM
)
Log-OK "deploy.sh dibuat (LF, tanpa BOM)"

# ---- Buat deploy.ps1 (Windows Server) ----------------------
$deployPs1Lines = @(
    "# deploy.ps1 - Script Collection Setup Script (Windows Server)",
    "# Jalankan sekali setelah ekstrak ZIP:",
    "#   .\deploy.ps1",
    "",
    '$ScriptDir = $PSScriptRoot',
    "Set-Location `$ScriptDir",
    "",
    'Write-Host ""',
    'Write-Host "  =============================================" -ForegroundColor Cyan',
    'Write-Host "    Script Collection - Deploy Setup          " -ForegroundColor White',
    'Write-Host "  =============================================" -ForegroundColor Cyan',
    'Write-Host ""',
    "",
    "# [1] Buat .env dari .env.example jika belum ada",
    'if (-Not (Test-Path ".env")) {',
    '    Copy-Item ".env.example" ".env"',
    '    Write-Warning ".env dibuat dari .env.example - sesuaikan app.baseURL!"',
    "}",
    "",
    "# [2] Pastikan folder writable/ ada",
    '$writableDirs = @("writable\cache","writable\logs","writable\session","writable\debugbar")',
    "foreach (`$d in `$writableDirs) {",
    '    if (-Not (Test-Path `$d)) { New-Item -ItemType Directory -Path `$d -Force | Out-Null }',
    "}",
    'Write-Host "  [OK] Folder writable/ siap" -ForegroundColor Green',
    "",
    "# [3] Pastikan folder database/ ada",
    'if (-Not (Test-Path "database")) { New-Item -ItemType Directory -Path "database" -Force | Out-Null }',
    'Write-Host "  [OK] Folder database/ siap" -ForegroundColor Green',
    "",
    "# [4] Jalankan migrasi",
    'Write-Host "  [>>] Menjalankan migrasi database..." -ForegroundColor Cyan',
    "php spark migrate --all",
    'Write-Host "  [OK] Migrasi selesai" -ForegroundColor Green',
    "",
    'Write-Host ""',
    'Write-Host "  =============================================" -ForegroundColor Green',
    'Write-Host "    Setup selesai!" -ForegroundColor Green',
    'Write-Host "    Document Root -> public\" -ForegroundColor White',
    'Write-Host "  =============================================" -ForegroundColor Green',
    'Write-Host ""'
)
$deployPs1Lines -join "`r`n" | Set-Content (Join-Path $TempDir "deploy.ps1") -Encoding UTF8
Log-OK "deploy.ps1 dibuat (Windows)"

# ---- Buat README.txt ---------------------------------------
$readmeLines = @(
    "==============================================================",
    "       Script Collection - Panduan Deploy",
    "       Framework: CodeIgniter 4 + SQLite3 + PHP 8.2+",
    "==============================================================",
    "",
    "Isi paket ini:",
    "  app/           -> Source code CodeIgniter 4",
    "  public/        -> Document root web server (arahkan ke sini)",
    "  vendor/        -> Dependensi PHP (Composer)",
    "  database/      -> SQLite database (scripts.db)",
    "  writable/      -> Cache, log, session (butuh permission write)",
    "  spark          -> CLI CodeIgniter 4",
    "  composer.json  -> Info dependensi project",
    "  .env.example   -> Template konfigurasi environment",
    "  deploy.sh      -> Setup otomatis Linux/cPanel",
    "  deploy.ps1     -> Setup otomatis Windows Server",
    "",
    "==============================================================",
    "[A] DEPLOY KE VPS LINUX (Apache/Nginx + PHP)",
    "==============================================================",
    "1. Upload dan ekstrak script-deploy.zip:",
    "     unzip script-deploy.zip -d /var/www/script-collection",
    "     cd /var/www/script-collection",
    "",
    "2. Jalankan setup otomatis:",
    "     bash deploy.sh",
    "",
    "3. Edit konfigurasi:",
    "     nano .env",
    "     # Ubah: app.baseURL = 'https://domain-anda.com/'",
    "     # Pastikan: CI_ENVIRONMENT = production",
    "",
    "4. Konfigurasi Apache VirtualHost:",
    "     <VirtualHost *:80>",
    "         ServerName domain-anda.com",
    "         DocumentRoot /var/www/script-collection/public",
    "         <Directory /var/www/script-collection/public>",
    "             AllowOverride All",
    "             Require all granted",
    "         </Directory>",
    "     </VirtualHost>",
    "",
    "   Atau Nginx:",
    "     server {",
    "         listen 80;",
    "         server_name domain-anda.com;",
    "         root /var/www/script-collection/public;",
    "         index index.php;",
    "         location / {",
    "             try_files $uri $uri/ /index.php$is_args$args;",
    "         }",
    "         location ~ \.php$ {",
    "             fastcgi_pass unix:/run/php/php8.2-fpm.sock;",
    "             fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;",
    "             include fastcgi_params;",
    "         }",
    "     }",
    "",
    "==============================================================",
    "[B] DEPLOY KE cPANEL SHARED HOSTING (PHP)",
    "==============================================================",
    "1. Upload script-deploy.zip via File Manager, ekstrak ke",
    "   folder home (misal: /home/user/script-collection/)",
    "",
    "2. Di cPanel -> Domains / Subdomains:",
    "   - Arahkan Document Root ke: script-collection/public",
    "",
    "3. Jalankan deploy.sh via cPanel Terminal:",
    "     cd ~/script-collection && bash deploy.sh",
    "",
    "4. Edit .env:",
    "   - app.baseURL sesuaikan dengan domain/subdomain Anda",
    "   - CI_ENVIRONMENT = production",
    "",
    "==============================================================",
    "[C] DEPLOY KE WINDOWS SERVER (IIS / Laragon)",
    "==============================================================",
    "1. Ekstrak ZIP ke folder web server:",
    "     C:\inetpub\wwwroot\script-collection\",
    "",
    "2. Jalankan setup:",
    "     cd C:\inetpub\wwwroot\script-collection",
    "     .\deploy.ps1",
    "",
    "3. Arahkan IIS Site ke folder: script-collection\public",
    "",
    "==============================================================",
    "CATATAN PENTING:",
    "  - Folder public/ adalah satu-satunya document root web server",
    "  - Folder app/, vendor/, database/ JANGAN bisa diakses publik",
    "  - Pastikan PHP 8.2+ aktif di server",
    "  - Ekstensi PHP yang dibutuhkan: pdo_sqlite, sqlite3, intl, mbstring",
    "  - Backup database/scripts.db secara berkala",
    "  - Akses admin: /admin/login",
    "=============================================================="
)
$readmeLines -join "`r`n" | Set-Content (Join-Path $TempDir "README.txt") -Encoding UTF8
Log-OK "README.txt dibuat"

# ---- [3/5] .gitignore untuk vendor/ di temp ----------------
# (tidak disertakan di ZIP — vendor sudah ada)

# ---- [4/5] Buat ZIP ----------------------------------------
Log-Step "4/5" "Membuat arsip $OutFile ..."

if (Test-Path $OutZip) {
    Remove-Item $OutZip -Force
    Log-Warn "File ZIP lama dihapus"
}

Add-Type -AssemblyName System.IO.Compression.FileSystem
[System.IO.Compression.ZipFile]::CreateFromDirectory(
    $TempDir,
    $OutZip,
    [System.IO.Compression.CompressionLevel]::Optimal,
    $false
)

if (-Not (Test-Path $OutZip)) { Log-Err "ZIP gagal dibuat." }

$ZipSizeKB = [math]::Round((Get-Item $OutZip).Length / 1KB, 0)
$ZipSizeMB = [math]::Round((Get-Item $OutZip).Length / 1MB, 2)
Log-OK "ZIP berhasil dibuat: $OutFile ($ZipSizeMB MB / $ZipSizeKB KB)"

# ---- [5/5] Bersihkan temp ----------------------------------
Log-Step "5/5" "Membersihkan file sementara..."
Remove-Item $TempDir -Recurse -Force
Log-OK "Temp folder dihapus"

# ---- Selesai -----------------------------------------------
Write-Host ""
Write-Host "  =============================================" -ForegroundColor Green
Write-Host "    Deploy package berhasil dibuat!           " -ForegroundColor Green
Write-Host "  =============================================" -ForegroundColor Green
Write-Host ""
Write-Host "  File   : $OutZip" -ForegroundColor White
Write-Host "  Ukuran : $ZipSizeMB MB ($ZipSizeKB KB)" -ForegroundColor White
Write-Host ""
Write-Host "  Isi ZIP:" -ForegroundColor DarkGray
Write-Host "    app/              -> Source CI4" -ForegroundColor DarkGray
Write-Host "    public/           -> Document Root (arahkan web server ke sini)" -ForegroundColor DarkGray
Write-Host "    vendor/           -> Dependensi PHP" -ForegroundColor DarkGray
Write-Host "    database/         -> SQLite database" -ForegroundColor DarkGray
Write-Host "    deploy.sh/.ps1    -> Setup script" -ForegroundColor DarkGray
Write-Host "    .env.example      -> Template konfigurasi" -ForegroundColor DarkGray
Write-Host "    README.txt        -> Panduan deploy lengkap" -ForegroundColor DarkGray
Write-Host ""
Write-Host "  Lihat README.txt di dalam ZIP untuk panduan deploy." -ForegroundColor DarkGray
Write-Host ""
