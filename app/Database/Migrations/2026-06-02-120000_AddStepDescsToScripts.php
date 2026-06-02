<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStepDescsToScripts extends Migration
{
    public function up(): void
    {
        // SQLite3: tambah kolom satu per satu
        $this->db->query('ALTER TABLE scripts ADD COLUMN step1_desc TEXT NULL DEFAULT NULL');
        $this->db->query('ALTER TABLE scripts ADD COLUMN step2_desc TEXT NULL DEFAULT NULL');
        $this->db->query('ALTER TABLE scripts ADD COLUMN step3_desc TEXT NULL DEFAULT NULL');
    }

    public function down(): void
    {
        // SQLite3 tidak mendukung DROP COLUMN secara langsung di versi lama
        // Biarkan kosong atau rekreasi tabel jika diperlukan
    }
}
