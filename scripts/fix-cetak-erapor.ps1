# =============================================================
#  Fix Cetak eRapor - Patch Installer
#  Script  : fix-cetak-erapor.ps1
# =============================================================

#Requires -Version 5.1

Clear-Host
Write-Host "=============================================================" -ForegroundColor Blue
Write-Host "   Fix Cetak eRapor - Patch Installer" -ForegroundColor White
Write-Host "=============================================================" -ForegroundColor Blue

# ── 1. Pastikan direktori aktif ──────────────────────────────
$targetDirWin = "C:\eRaporSMK\dataweb"
$isWin = $IsWindows
if ($null -eq $isWin) {
    $isWin = [System.Environment]::OSVersion.Platform -match "^Win"
}

if ($isWin) {
    if (Test-Path $targetDirWin) {
        Set-Location $targetDirWin
        Write-Host "[OK] Pindah ke direktori: $targetDirWin" -ForegroundColor Green
    } else {
        Write-Host "[WARNING] Direktori $targetDirWin tidak ditemukan." -ForegroundColor Yellow
        Write-Host "       Pastikan saat ini Anda berada di direktori eRaporSMK atau root Erapor." -ForegroundColor Yellow
    }
} else {
    Write-Host "[OK] Berjalan di Linux/Hosting. Direktori saat ini: $((Get-Location).Path)" -ForegroundColor Green
    Write-Host "     Pastikan ini adalah root folder eRapor." -ForegroundColor Yellow
}

# ── 2. Download file erapor-smk.zip ──────────────────────────
$GDriveFileId = "1oMNL6SGs6Kuc8h6BOgu59brEzWPEfQqO"
$ZipFile = "erapor-smk.zip"
# Menggunakan format Google Drive Download URL yang bypass warning (untuk file kecil/sedang)
$downloadUrl = "https://drive.usercontent.google.com/download?id=$GDriveFileId&export=download&authuser=0&confirm=t"

Write-Host "`n[>>] Mengunduh file $ZipFile dari Google Drive..." -ForegroundColor Cyan
try {
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
    Invoke-WebRequest -Uri $downloadUrl -OutFile $ZipFile -UseBasicParsing -ErrorAction Stop
    Write-Host "[OK] Unduhan selesai." -ForegroundColor Green
} catch {
    Write-Host "[!!] Gagal mengunduh file: $_" -ForegroundColor Red
    exit
}

# ── 3. Ekstrak isi file erapor-smk.zip ───────────────────────
Write-Host "`n[>>] Mengekstrak $ZipFile..." -ForegroundColor Cyan
try {
    Expand-Archive -Path $ZipFile -DestinationPath . -Force -ErrorAction Stop
    Write-Host "[OK] Ekstraksi berhasil." -ForegroundColor Green
} catch {
    Write-Host "[!!] Gagal mengekstrak file: $_" -ForegroundColor Red
    exit
}

# ── 4. Jalankan Patch sesuai OS ──────────────────────────────
Write-Host "`n[>>] Menjalankan Patch..." -ForegroundColor Cyan
if ($isWin) {
    if (Test-Path "patch-windows.ps1") {
        Write-Host "     Menjalankan patch-windows.ps1..." -ForegroundColor Gray
        & ".\patch-windows.ps1"
    } elseif (Test-Path "patch-windows.bat") {
        Write-Host "     Menjalankan patch-windows.bat..." -ForegroundColor Gray
        & $env:ComSpec /c "patch-windows.bat"
    } else {
        Write-Host "[WARNING] File patch-windows.bat atau patch-windows.ps1 tidak ditemukan." -ForegroundColor Yellow
    }
} else {
    if (Test-Path "patch-linux.sh") {
        Write-Host "     Menjalankan patch-linux.sh..." -ForegroundColor Gray
        bash -c "chmod +x patch-linux.sh && ./patch-linux.sh"
    } else {
        Write-Host "[WARNING] File patch-linux.sh tidak ditemukan." -ForegroundColor Yellow
    }
}

# ── 5. Hapus file instalasi ──────────────────────────────────
Write-Host "`n[>>] Membersihkan file instalasi..." -ForegroundColor Cyan
$filesToDelete = @("patch-cetak.zip", "patch-linux.sh", "patch-windows.bat", "patch-windows.ps1", "erapor-smk.zip")
foreach ($file in $filesToDelete) {
    if (Test-Path $file) {
        Remove-Item -Path $file -Force -ErrorAction SilentlyContinue
        Write-Host "     Dihapus: $file" -ForegroundColor Gray
    }
}

Write-Host "`n=============================================================" -ForegroundColor Blue
Write-Host "   Patch berhasil diterapkan!" -ForegroundColor Green
Write-Host "=============================================================" -ForegroundColor Blue
Write-Host ""
