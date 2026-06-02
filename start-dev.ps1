# ============================================================
#  start-dev.ps1 - CodeIgniter 4 Dev Runner
#  Jalankan dengan: .\start-dev.ps1
# ============================================================

$ProjectDir = $PSScriptRoot
Set-Location $ProjectDir

Write-Host ""
Write-Host "  Script Collection - CodeIgniter 4 Dev Runner" -ForegroundColor Cyan
Write-Host "  ============================================" -ForegroundColor DarkGray
Write-Host ""

# 1. Cek PHP
$phpCheck = Get-Command php -ErrorAction SilentlyContinue
if (-not $phpCheck) {
    Write-Host "  [ERROR] PHP tidak ditemukan di sistem Anda." -ForegroundColor Red
    Write-Host "  Pastikan PHP 8.2+ sudah terinstall dan ditambahkan ke PATH." -ForegroundColor Yellow
    exit 1
}
$phpVer = (php -r "echo PHP_VERSION;")
Write-Host "  [1/4] PHP v$phpVer ditemukan." -ForegroundColor Green

# 2. Cek Composer
$composerCheck = Get-Command composer -ErrorAction SilentlyContinue
if (-not $composerCheck) {
    Write-Host "  [ERROR] Composer tidak ditemukan." -ForegroundColor Red
    Write-Host "  Pastikan Composer sudah terinstall dan ditambahkan ke PATH." -ForegroundColor Yellow
    exit 1
}
Write-Host "  [2/4] Composer ditemukan." -ForegroundColor Green

# 3. Cek vendor (composer dependencies)
if (-Not (Test-Path "$ProjectDir\vendor")) {
    Write-Host "  [3/4] Folder vendor tidak ditemukan. Menjalankan composer install..." -ForegroundColor Yellow
    composer install
    if ($LASTEXITCODE -ne 0) {
        Write-Host ""
        Write-Host "  [ERROR] composer install gagal." -ForegroundColor Red
        exit 1
    }
    Write-Host "  [3/4] Dependencies berhasil diinstall." -ForegroundColor Green
} else {
    Write-Host "  [3/4] Folder vendor sudah ada. Skip install." -ForegroundColor Green
}

# 4. Cek .env
if (-Not (Test-Path "$ProjectDir\.env")) {
    if (Test-Path "$ProjectDir\env") {
        Write-Host "  [4/4] .env tidak ditemukan. Menyalin dari template env..." -ForegroundColor Yellow
        Copy-Item "$ProjectDir\env" "$ProjectDir\.env"
        Write-Host "  [4/4] .env berhasil dibuat." -ForegroundColor Green
    } else {
        Write-Host "  [4/4] Warning: Template env tidak ditemukan." -ForegroundColor Yellow
    }
} else {
    Write-Host "  [4/4] File .env sudah ada." -ForegroundColor Green
}

Write-Host ""
Write-Host "  Environment siap!" -ForegroundColor Green
Write-Host "  ============================================" -ForegroundColor DarkGray
Write-Host "  CodeIgniter 4 Development Server sedang berjalan." -ForegroundColor White
Write-Host "  Aplikasi Anda dilayani secara lokal via Nginx (Reverse Proxy) di:" -ForegroundColor White
Write-Host "  URL: https://script-mini.test/" -ForegroundColor Cyan
Write-Host ""
Write-Host "  Membuka browser ke https://script-mini.test/ ..." -ForegroundColor Gray
Start-Process "https://script-mini.test/"
Write-Host ""
Write-Host "  Menjalankan php spark serve (Tekan Ctrl+C untuk berhenti)..." -ForegroundColor Yellow
php spark serve --host 127.0.0.1 --port 8080
Write-Host ""
