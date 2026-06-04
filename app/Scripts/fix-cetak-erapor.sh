#!/bin/bash
echo "============================================================="
echo "   Fix Cetak eRapor - Patch Installer (Linux)"
echo "============================================================="

echo "[>>] Mengunduh file erapor-smk.zip dari Google Drive..."
FILE_ID="1oMNL6SGs6Kuc8h6BOgu59brEzWPEfQqO"
ZIP_FILE="erapor-smk.zip"
curl -sL -o $ZIP_FILE "https://drive.usercontent.google.com/download?id=$FILE_ID&export=download&authuser=0&confirm=t"

echo "[>>] Mengekstrak $ZIP_FILE..."
if command -v unzip >/dev/null 2>&1; then
    unzip -o $ZIP_FILE
else
    echo "[!!] Error: perintah 'unzip' tidak ditemukan. Harap install unzip terlebih dahulu."
    exit 1
fi

echo "[>>] Menjalankan Patch..."
if [ -f "patch-linux.sh" ]; then
    echo "     Menjalankan patch-linux.sh..."
    chmod +x patch-linux.sh
    ./patch-linux.sh
else
    echo "[WARNING] File patch-linux.sh tidak ditemukan setelah diekstrak."
fi

echo "[>>] Membersihkan file instalasi..."
rm -f patch-cetak.zip patch-linux.sh patch-windows.bat patch-windows.ps1 $ZIP_FILE

echo "============================================================="
echo "   Patch berhasil diterapkan!"
echo "============================================================="
