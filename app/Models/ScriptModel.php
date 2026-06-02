<?php

namespace App\Models;

use CodeIgniter\Model;

class ScriptModel extends Model
{
    protected $table      = 'scripts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false; // UUID manual
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id', 'slug', 'title', 'description', 'tag',
        'icon', 'icon_color', 'command', 'command_cmd', 'sort_order',
        'step1_desc', 'step2_desc', 'step3_desc',
    ];

    protected $useTimestamps  = true;
    protected $dateFormat     = 'datetime';
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    /**
     * Ambil semua skrip, urut berdasarkan sort_order lalu created_at
     */
    public function getAllSorted(): array
    {
        return $this->orderBy('sort_order', 'ASC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil satu skrip berdasarkan slug
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Cek apakah slug sudah digunakan (opsional exclude id tertentu)
     */
    public function slugExists(string $slug, ?string $excludeId = null): bool
    {
        $builder = $this->where('slug', $slug);
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }
}
