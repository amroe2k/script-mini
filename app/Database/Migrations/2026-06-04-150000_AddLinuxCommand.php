<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLinuxCommand extends Migration
{
    public function up(): void
    {
        // SQLite3: tambah kolom satu per satu
        $this->db->query('ALTER TABLE scripts ADD COLUMN enable_linux INTEGER NOT NULL DEFAULT 0');
        $this->db->query('ALTER TABLE scripts ADD COLUMN command_linux TEXT NULL DEFAULT NULL');
    }

    public function down(): void
    {
        // SQLite3 tidak mendukung DROP COLUMN secara langsung di versi lama
    }
}
