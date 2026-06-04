# =============================================================
#  Fix Synchronizer eRapor - Patch Installer
#  Script  : fix-synchronizer.ps1
#  Tujuan  : Download dataweb.zip, stop service, ekstrak,
#            lalu jalankan kembali service
#  Folder  : C:\synchronizer\dataweb
# =============================================================
#  CARA PENGGUNAAN:
#    PowerShell : .\fix-synchronizer.ps1
#    CMD        : powershell -ExecutionPolicy Bypass -File "fix-synchronizer.ps1"
# =============================================================

#Requires -Version 5.1

# ── Konfigurasi ──────────────────────────────────────────────
$GDriveFileId = "1O_Lj8OMOsCR9v_dJKG4UY8HQFb05kFD0"
$GDriveBase   = "https://drive.google.com/uc?export=download"
$TargetDir    = "C:\synchronizer\dataweb"
$BaseDir      = "C:\synchronizer"
$ZipFile      = "$BaseDir\dataweb.zip"
$SvcName    = "DapodikSynchronizerWebSrv"

# ── Helper: Warna output ──────────────────────────────────────
function Write-Step  { param($msg) Write-Host "`n[>>] $msg" -ForegroundColor Cyan }
function Write-Ok    { param($msg) Write-Host "[OK] $msg"   -ForegroundColor Green }
function Write-Fail  { param($msg) Write-Host "[!!] $msg"   -ForegroundColor Red; throw "Proses dihentikan." }
function Write-Info  { param($msg) Write-Host "     $msg"   -ForegroundColor Gray }
function Write-Warn  { param($msg) Write-Host "[**] $msg"   -ForegroundColor Yellow }

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

    # URL langsung ke file (bypass halaman konfirmasi virus scan)
    $downloadUrl = "https://drive.usercontent.google.com/download?id=$GDriveFileId&export=download&authuser=0&confirm=t"
    Write-Info "Menghubungi Google Drive..."

    # ── Download streaming dengan progress bar ─────────────────
    $reqDl              = [System.Net.HttpWebRequest]::Create($downloadUrl)
    $reqDl.Method       = "GET"
    $reqDl.Timeout      = 600000   # 10 menit
    $reqDl.UserAgent    = "Mozilla/5.0"
    $reqDl.AllowAutoRedirect = $true

    $response   = $reqDl.GetResponse()
    $totalBytes = $response.ContentLength
    $totalMB    = [math]::Round($totalBytes / 1MB, 1)
    $readStream = $response.GetResponseStream()
    $fileStream = [System.IO.File]::Create($ZipFile)
    $buffer     = New-Object byte[] 65536   # 64KB per chunk
    $totalRead  = 0
    $stopwatch  = [System.Diagnostics.Stopwatch]::StartNew()

    do {
        $bytesRead = $readStream.Read($buffer, 0, $buffer.Length)
        if ($bytesRead -gt 0) {
            $fileStream.Write($buffer, 0, $bytesRead)
            $totalRead += $bytesRead
            $readMB    = [math]::Round($totalRead / 1MB, 1)
            $pct       = if ($totalBytes -gt 0) { [int]($totalRead * 100 / $totalBytes) } else { -1 }
            $elapsed   = $stopwatch.Elapsed.TotalSeconds
            $speedKB   = if ($elapsed -gt 0) { [math]::Round($totalRead / 1KB / $elapsed, 0) } else { 0 }
            $status    = if ($totalBytes -gt 0) {
                "$readMB MB / $totalMB MB  •  ${speedKB} KB/s"
            } else {
                "$readMB MB diunduh  •  ${speedKB} KB/s"
            }
            Write-Progress -Activity "Mengunduh dataweb.zip dari Google Drive..." `
                           -Status $status -PercentComplete $pct
        }
    } while ($bytesRead -gt 0)

    $fileStream.Close()
    $readStream.Close()
    $response.Close()
    $stopwatch.Stop()
    Write-Progress -Activity "Mengunduh dataweb.zip dari Google Drive..." -Completed

    # Validasi: pastikan yang diunduh adalah ZIP, bukan HTML
    $actualSizeBytes = (Get-Item $ZipFile).Length
    if ($actualSizeBytes -lt 10KB) {
        Remove-Item $ZipFile -Force -ErrorAction SilentlyContinue
        Write-Fail "File yang diunduh terlalu kecil ($actualSizeBytes bytes) — kemungkinan bukan file ZIP valid. Pastikan file di Google Drive bersifat publik."
    }

    $sizeMB  = [math]::Round($actualSizeBytes / 1MB, 2)
    $elapsed = [math]::Round($stopwatch.Elapsed.TotalSeconds, 1)
    Write-Ok "dataweb.zip berhasil diunduh ($sizeMB MB dalam ${elapsed}s)"
} catch {
    if ($fileStream) { $fileStream.Close() }
    if ($readStream) { $readStream.Close() }
    Write-Progress -Activity "Mengunduh dataweb.zip dari Google Drive..." -Completed -ErrorAction SilentlyContinue
    Write-Fail "Gagal mengunduh dari Google Drive: $_"
}

# ── Langkah 3: Persiapan database.sqlite ─────────────────────
Write-Step "Memeriksa file database.sqlite..."
$dbDir = "$TargetDir\database"
if (-not (Test-Path $dbDir)) {
    New-Item -ItemType Directory -Path $dbDir -Force | Out-Null
    Write-Info "Folder database/ dibuat."
}
if (-not (Test-Path "$dbDir\database.sqlite")) {
    New-Item -ItemType File -Path "$dbDir\database.sqlite" -Force | Out-Null
    Write-Ok "database.sqlite (kosong) dibuat untuk instalasi baru."
} else {
    Write-Info "database.sqlite sudah ada — tidak diubah."
}

# ── Langkah 4: Stop service sebelum ekstrak ───────────────────
Write-Step "Menghentikan service: $SvcName"
$svcFound      = $false
$svcWasRunning = $false
$svcActualName = $SvcName   # nama aktual yang berhasil ditemukan

# Coba temukan service — pertama dengan nama tepat, lalu wildcard
$svc = $null
try { $svc = Get-Service -Name $SvcName -ErrorAction Stop } catch {}
if (-not $svc) {
    # Wildcard: cari nama yang mengandung kata kunci utama
    $keyword = ($SvcName -split 'Synchronizer')[0] + 'Synchronizer'
    $found   = @(Get-Service -ErrorAction SilentlyContinue | Where-Object { $_.Name -like "*Synchronizer*" -or $_.DisplayName -like "*Synchronizer*" })
    if ($found.Count -eq 1) {
        $svc           = $found[0]
        $svcActualName = $svc.Name
        Write-Warn "Nama service berbeda — ditemukan: '$svcActualName'  (dicari: '$SvcName')"
    } elseif ($found.Count -gt 1) {
        # Pilih yang paling mirip
        $svc           = $found | Sort-Object { [Math]::Abs($_.Name.Length - $SvcName.Length) } | Select-Object -First 1
        $svcActualName = $svc.Name
        Write-Warn "Beberapa service Synchronizer ditemukan, menggunakan: '$svcActualName'"
    }
}

if ($svc) {
    $svcFound = $true
    Write-Info "Service ditemukan: '$svcActualName' (Status: $($svc.Status))"
    if ($svc.Status -eq 'Running') {
        $svcWasRunning = $true
        try {
            Stop-Service -Name $svcActualName -Force -ErrorAction Stop
            $svc.WaitForStatus('Stopped', (New-TimeSpan -Seconds 30))
            Write-Ok "Service '$svcActualName' berhasil dihentikan."
        } catch [System.ServiceProcess.TimeoutException] {
            Write-Warn "Service tidak berhenti dalam 30 detik — melanjutkan paksa..."
        } catch {
            # Fallback: gunakan net stop
            Write-Info "Mencoba net stop sebagai fallback..."
            $netResult = & net stop "$svcActualName" 2>&1
            if ($LASTEXITCODE -eq 0) {
                Write-Ok "Service '$svcActualName' dihentikan via net stop."
            } else {
                Write-Warn "Gagal menghentikan service: $netResult"
            }
        }
    } else {
        Write-Info "Service '$svcActualName' sudah berhenti (status: $($svc.Status))."
    }
} else {
    Write-Warn "Service '$SvcName' tidak ditemukan — service stop dilewati."
}

# ── Langkah 5: Ekstrak dataweb.zip ────────────────────────────
Write-Step "Mengekstrak dataweb.zip ke $TargetDir ..."
try {
    Add-Type -AssemblyName System.IO.Compression.FileSystem

    $zip = [System.IO.Compression.ZipFile]::OpenRead($ZipFile)
    $skipped = 0
    $extracted = 0

    foreach ($entry in $zip.Entries) {
        $destPath = Join-Path $TargetDir $entry.FullName.Replace('/', '\')

        # Lindungi file sensitif — jangan pernah ditimpa
        $protectedFiles = @(
            '(?i)(^|[\/])database\.sqlite$',   # database utama
            '(?i)(^|[\/])\.env$'               # konfigurasi environment
        )
        $isProtected = $false
        foreach ($pattern in $protectedFiles) {
            if ($entry.FullName -match $pattern) {
                Write-Info "Dilewati (dilindungi): $($entry.FullName)"
                $skipped++
                $isProtected = $true
                break
            }
        }
        if ($isProtected) { continue }

        # Entry folder (nama diakhiri /) — buat direktori
        if ($entry.Name -eq '') {
            if (-not (Test-Path $destPath)) {
                New-Item -ItemType Directory -Path $destPath -Force | Out-Null
            }
            continue
        }

        # Pastikan folder tujuan ada
        $destDir = Split-Path $destPath -Parent
        if (-not (Test-Path $destDir)) {
            New-Item -ItemType Directory -Path $destDir -Force | Out-Null
        }

        # Ekstrak file dengan overwrite ($true)
        [System.IO.Compression.ZipFileExtensions]::ExtractToFile($entry, $destPath, $true)
        $extracted++
    }
    $zip.Dispose()

    Write-Ok "Ekstraksi selesai: $extracted file diekstrak, $skipped dilewati."
} catch {
    # Pastikan zip tertutup sebelum exit
    if ($zip) { try { $zip.Dispose() } catch {} }
    if ($svcFound) { Start-Service -Name $SvcName -ErrorAction SilentlyContinue }
    Write-Fail "Gagal mengekstrak dataweb.zip: $_"
}

# ── Langkah 6: Setup .env & Migrasi Database (Laravel) ───────
Write-Step "Menyiapkan konfigurasi Laravel (.env + database)..."
$envFile     = "$TargetDir\.env"
$envExample  = "$TargetDir\.env.example"
$artisan     = "$TargetDir\artisan"
$phpExe      = Get-Command php -ErrorAction SilentlyContinue

if (-not $phpExe) {
    Write-Warn "PHP tidak ditemukan di PATH — langkah setup Laravel dilewati."
} else {
    # 6a. Buat .env dari .env.example HANYA jika .env belum ada
    #     .env TIDAK PERNAH ditimpa — berisi konfigurasi spesifik mesin
    if (-not (Test-Path $envFile)) {
        if (Test-Path $envExample) {
            Copy-Item $envExample $envFile -Force
            Write-Ok ".env dibuat dari .env.example"
        } else {
            Write-Warn ".env.example tidak ditemukan — .env tidak dibuat."
        }
    } else {
        Write-Ok ".env sudah ada dan TIDAK diubah (terlindungi)."
    }

    # 6b. Generate APP_KEY jika masih kosong
    if (Test-Path $envFile) {
        $appKey = (Get-Content $envFile | Where-Object { $_ -match '^APP_KEY=' }) -replace 'APP_KEY=', ''
        if (-not $appKey -or $appKey.Trim() -eq '') {
            Push-Location $TargetDir
            & php artisan key:generate --force 2>&1 | Out-Null
            Pop-Location
            Write-Ok "APP_KEY berhasil di-generate."
        } else {
            Write-Info "APP_KEY sudah ada — tidak diubah."
        }
    }

    # 6c. Jalankan migrasi database
    if (Test-Path $artisan) {
        Push-Location $TargetDir
        Write-Info "Menjalankan: php artisan migrate --force ..."
        $migrateOutput = & php artisan migrate --force 2>&1
        Pop-Location
        if ($LASTEXITCODE -eq 0) {
            Write-Ok "Migrasi database selesai."
        } else {
            Write-Warn "Migrasi selesai dengan peringatan:`n$migrateOutput"
        }
    } else {
        Write-Warn "artisan tidak ditemukan — migrasi dilewati."
    }
}

# ── Langkah 7: Start kembali service ─────────────────────────
Write-Step "Menjalankan kembali service: $svcActualName"
if ($svcFound) {
    try {
        Start-Service -Name $svcActualName -ErrorAction Stop
        $svc2 = Get-Service -Name $svcActualName
        $svc2.WaitForStatus('Running', (New-TimeSpan -Seconds 30))
        Write-Ok "Service '$svcActualName' berhasil dijalankan kembali."
    } catch {
        # Fallback: gunakan net start
        Write-Info "Mencoba net start sebagai fallback..."
        $netResult = & net start "$svcActualName" 2>&1
        if ($LASTEXITCODE -eq 0) {
            Write-Ok "Service '$svcActualName' dijalankan via net start."
        } else {
            Write-Warn "Gagal menjalankan '$svcActualName': $netResult"
            Write-Info "Coba jalankan manual: net start \"$svcActualName\""
        }
    }
} else {
    Write-Info "Service tidak ditemukan di sistem ini — dilewati."
}

# ── Langkah 8: Bersihkan file ZIP sementara ──────────────────
Write-Step "Membersihkan file sementara..."
try {
    Remove-Item $ZipFile -Force -ErrorAction Stop
    Write-Ok "dataweb.zip dihapus dari $BaseDir"
} catch {
    Write-Info "Catatan: Gagal menghapus $ZipFile (bisa dihapus manual)."
}

# ── Selesai ───────────────────────────────────────────────────
Write-Host ""
Write-Host "=============================================================" -ForegroundColor Blue
Write-Host "   Proses selesai! Folder target: $TargetDir" -ForegroundColor Green
Write-Host "   Akses : http://localhost:7008/" -ForegroundColor Cyan
Write-Host "=============================================================" -ForegroundColor Blue
Write-Host ""
