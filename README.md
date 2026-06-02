# Script Collection (Script-Mini)

Aplikasi manajemen script sederhana yang dibangun menggunakan **CodeIgniter 4** dan **SQLite3**. Aplikasi ini dirancang untuk meng-hosting, mengelola, dan membagikan skrip (seperti PowerShell `.ps1`) secara terpusat dari server Anda. Dilengkapi dengan fitur *syntax highlighting*, *copy-to-clipboard*, mode gelap (Dark Mode), dan panduan eksekusi multi-langkah (Multi-step).

## 🚀 Fitur Utama

- **File Management & Multi-step Guides (✨ Baru)**: Upload (dukungan Drag & Drop), hapus, dan kelola skrip PowerShell (`.ps1`). Kini Anda dapat menambahkan deskripsi panduan langkah demi langkah (Step 1, Step 2, Step 3) untuk setiap eksekusi script.
- **Modern UI/UX & Custom Error Pages (✨ Baru)**: Tampilan panel admin yang bersih dan responsif, mendukung transisi mulus antara *Dark* dan *Light Mode*. Dilengkapi dengan kustomisasi halaman Error 404 & Exceptions yang informatif serta Favicon kustom.
- **Live Preview Modal**: Tinjau kode script langsung dari browser dengan *PowerShell syntax highlighting*.
- **Quick Run Commands**: Menyediakan perintah *one-liner* (seperti `Invoke-RestMethod`) untuk mempermudah eksekusi script langsung di terminal klien.
- **Portable Database**: Menggunakan SQLite3 yang sangat ringan dan tidak memerlukan instalasi server database eksternal.

## 📦 Persyaratan Sistem & Server

- **PHP 8.2** atau yang lebih baru (Disarankan PHP 8.3).
- **Ekstensi PHP**: `pdo_sqlite`, `sqlite3`, `intl`, `mbstring`, `json`, `curl`.
- Web server (Apache/Nginx/LiteSpeed) dengan *Document Root* yang diarahkan ke folder `public/`.

---

## ⚙️ Pengembangan Lokal (Development)

Untuk menjalankan dan mengembangkan aplikasi ini di komputer lokal dengan cepat, Anda dapat menggunakan *CodeIgniter 4 Dev Runner*:

1. Clone repository ini ke komputer lokal Anda.
2. Buka PowerShell pada folder project.
3. Jalankan skrip runner otomatis berikut:
   ```powershell
   .\start-dev.ps1
   ```
   *Skrip ini akan otomatis melakukan pengecekan PHP, instalasi dependensi Composer, pembuatan file `.env`, dan menjalankan server lokal (php spark serve).*
4. Lakukan migrasi database (jika database masih kosong):
   ```bash
   php artisan migrate --force # (Atau php spark migrate --all untuk CI4)
   ```
   > **Catatan:** Pada CI4 gunakan `php spark migrate --all`. Skrip `start-dev.ps1` akan melayani aplikasi di `http://localhost:8080` atau reverse proxy yang disiapkan.

---

## 🛠️ Deployment Otomatis (Shared Hosting / VPS)

Project ini memiliki *auto-deploy script* khusus berbasis PowerShell yang memungkinkan build, kompresi, upload (via SCP), ekstrak, dan migrasi (via SSH) hanya dalam **satu perintah**.

### Menggunakan `deploy-ssh.ps1`

Buka PowerShell di lokal komputer Anda pada root folder project, lalu jalankan:

```powershell
# [Mode Aman - Default] Deploy dan update kode saja (database & .htaccess server dipertahankan)
.\deploy-ssh.ps1

# [Mode Overwrite] Deploy & TIMPA database server dengan database lokal (HATI-HATI)
.\deploy-ssh.ps1 -OverwriteDB

# Opsi Lainnya:
.\deploy-ssh.ps1 -SkipBuild     # Gunakan ZIP lama, skip proses build/composer
.\deploy-ssh.ps1 -SkipMigrate   # Jangan jalankan migrasi database setelah ekstrak
```

> **Catatan:** 
> - Pastikan Anda sudah mengatur autentikasi SSH (*SSH keys*) ke server hosting agar tidak perlu login manual.
> - Script ini otomatis mengecualikan file `.env` dan `.htaccess` (root maupun public) agar pengaturan hosting Anda tidak rusak.

---

## 📦 Build Paket Manual (cPanel / FTP)

Jika Anda tidak memiliki akses SSH dan ingin meng-upload file secara manual lewat cPanel File Manager:

```powershell
.\build-deploy.ps1
```

Perintah di atas akan menjalankan optimasi Composer dan mengemas project ke dalam file `script-deploy.zip` (~1.4 MB) yang siap di-upload ke server.
Di dalam file ZIP tersebut juga sudah disertakan `README.txt` yang berisi instruksi deployment manual.

---

## 🔒 Keamanan

- **PENTING**: *Document Root* pada konfigurasi web server Anda **HARUS** di-set secara eksklusif ke folder `public/`.
- Jangan pernah mengekspos folder `app/`, `vendor/`, atau `writable/` ke ruang lingkup publik, mengingat file database `scripts.db` tersimpan di dalam folder `writable/`.
