<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ScriptModel;

class Scripts extends BaseController
{
    protected $scriptModel;

    public function __construct()
    {
        $this->scriptModel = new ScriptModel();
    }

    public function index()
    {
        $data = [
            'scripts'    => $this->scriptModel->getAllSorted(),
            'activeMenu' => 'scripts',
        ];
        return view('admin/scripts/index', $data);
    }

    public function files()
    {
        $scriptsDir = APPPATH . 'Scripts' . DIRECTORY_SEPARATOR;
        $ps1Files = [];
        if (is_dir($scriptsDir)) {
            foreach (glob($scriptsDir . '*.ps1') as $f) {
                $ps1Files[] = [
                    'name'     => basename($f),
                    'size'     => filesize($f),
                    'modified' => date('d M Y H:i', filemtime($f)),
                    'url'      => base_url('scripts/' . pathinfo(basename($f), PATHINFO_FILENAME) . '.ps1'),
                ];
            }
        }

        return view('admin/scripts/files', [
            'ps1Files'   => $ps1Files,
            'title'      => 'Hosted Files',
            'activeMenu' => 'files',
        ]);
    }

    public function new()
    {
        return view('admin/scripts/new');
    }

    public function create()
    {
        $title       = $this->request->getPost('title') ?? '';
        $slug        = $this->request->getPost('slug') ?? '';
        if (empty($slug)) {
            $slug = mb_url_title($title, '-', true);
        }
        
        $description = $this->request->getPost('description') ?? '';
        $tag         = $this->request->getPost('tag') ?? 'powershell';
        $icon        = $this->request->getPost('icon') ?? 'tool';
        $icon_color  = $this->request->getPost('icon_color') ?? 'blue';
        $command     = $this->request->getPost('command') ?? '';
        $command_cmd = $this->request->getPost('command_cmd') ?? '';
        $sort_order  = (int) ($this->request->getPost('sort_order') ?? 0);
        $step1_desc  = $this->request->getPost('step1_desc') ?? '';
        $step2_desc  = $this->request->getPost('step2_desc') ?? '';
        $step3_desc  = $this->request->getPost('step3_desc') ?? '';
        $enable_linux = $this->request->getPost('enable_linux') ? 1 : 0;
        $command_linux = $this->request->getPost('command_linux') ?? '';

        // Simple UUID v4 generator
        $id = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        $data = [
            'id'          => $id,
            'slug'        => $slug,
            'title'       => $title,
            'description' => $description,
            'tag'         => $tag,
            'icon'        => $icon,
            'icon_color'  => $icon_color,
            'command'     => $command,
            'command_cmd' => $command_cmd,
            'sort_order'  => $sort_order,
            'step1_desc'  => $step1_desc ?: null,
            'step2_desc'  => $step2_desc ?: null,
            'step3_desc'  => $step3_desc ?: null,
            'enable_linux'=> $enable_linux,
            'command_linux'=> $command_linux,
        ];

        try {
            $this->scriptModel->insert($data);
            return redirect()->to('/admin/scripts')->with('msg', 'created');
        } catch (\Exception $e) {
            $error = strpos($e->getMessage(), 'UNIQUE') !== false 
                ? 'Slug sudah digunakan, gunakan slug lain.' 
                : 'Terjadi kesalahan. Coba lagi.';
            return redirect()->back()->withInput()->with('error', $error);
        }
    }

    public function edit($id)
    {
        $script = $this->scriptModel->find($id);
        if (!$script) {
            return redirect()->to('/admin/scripts');
        }

        return view('admin/scripts/edit', ['script' => $script]);
    }

    public function update($id)
    {
        $script = $this->scriptModel->find($id);
        if (!$script) {
            return redirect()->to('/admin/scripts');
        }

        $title       = $this->request->getPost('title') ?? '';
        $slug        = $this->request->getPost('slug') ?? '';
        if (empty($slug)) {
            $slug = mb_url_title($title, '-', true);
        }
        $description = $this->request->getPost('description') ?? '';
        $tag         = $this->request->getPost('tag') ?? 'powershell';
        $icon        = $this->request->getPost('icon') ?? 'tool';
        $icon_color  = $this->request->getPost('icon_color') ?? 'blue';
        $command     = $this->request->getPost('command') ?? '';
        $command_cmd = $this->request->getPost('command_cmd') ?? '';
        $sort_order  = (int) ($this->request->getPost('sort_order') ?? 0);
        $step1_desc  = $this->request->getPost('step1_desc') ?? '';
        $step2_desc  = $this->request->getPost('step2_desc') ?? '';
        $step3_desc  = $this->request->getPost('step3_desc') ?? '';
        $enable_linux = $this->request->getPost('enable_linux') ? 1 : 0;
        $command_linux = $this->request->getPost('command_linux') ?? '';

        $data = [
            'slug'        => $slug,
            'title'       => $title,
            'description' => $description,
            'tag'         => $tag,
            'icon'        => $icon,
            'icon_color'  => $icon_color,
            'command'     => $command,
            'command_cmd' => $command_cmd,
            'sort_order'  => $sort_order,
            'step1_desc'  => $step1_desc ?: null,
            'step2_desc'  => $step2_desc ?: null,
            'step3_desc'  => $step3_desc ?: null,
            'enable_linux'=> $enable_linux,
            'command_linux'=> $command_linux,
        ];

        try {
            $this->scriptModel->update($id, $data);
            return redirect()->to('/admin/scripts')->with('msg', 'updated');
        } catch (\Exception $e) {
            $error = 'Gagal menyimpan: ' . $e->getMessage();
            if (strpos($e->getMessage(), 'UNIQUE') !== false) {
                $error = 'Slug sudah digunakan oleh script lain.';
            } elseif (strpos($e->getMessage(), 'NOT NULL') !== false) {
                $error = 'Ada field wajib yang kosong.';
            }
            return redirect()->back()->withInput()->with('error', $error);
        }
    }

    public function delete($id)
    {
        $this->scriptModel->delete($id);
        return redirect()->to('/admin/scripts')->with('msg', 'deleted');
    }

    // ── File Management (app/Scripts/) ──────────────────────────

    public function uploadFile()
    {
        $file = $this->request->getFile('script_file');

        if (!$file || !$file->isValid()) {
            return redirect()->to('/admin/scripts/files')
                ->with('file_error', 'File tidak valid atau tidak dipilih.');
        }

        // Hanya izinkan file .ps1
        if (strtolower($file->getClientExtension()) !== 'ps1') {
            return redirect()->to('/admin/scripts/files')
                ->with('file_error', 'Hanya file .ps1 yang diizinkan.');
        }

        // Sanitasi nama file: hanya huruf, angka, dash, underscore, titik
        $filename = preg_replace('/[^a-zA-Z0-9_\-.]/', '-', $file->getClientName());
        if (!str_ends_with(strtolower($filename), '.ps1')) {
            $filename .= '.ps1';
        }

        $scriptsDir = APPPATH . 'Scripts' . DIRECTORY_SEPARATOR;
        if (!is_dir($scriptsDir)) {
            mkdir($scriptsDir, 0755, true);
        }

        try {
            $file->move($scriptsDir, $filename, true); // overwrite jika ada
            return redirect()->to('/admin/scripts/files')
                ->with('file_msg', "File <strong>{$filename}</strong> berhasil diupload.");
        } catch (\Exception $e) {
            return redirect()->to('/admin/scripts/files')
                ->with('file_error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    public function generateFromFile($filename)
    {
        // Proteksi path traversal
        if (str_contains($filename, '/') || str_contains($filename, '\\') || str_contains($filename, '..')) {
            return redirect()->to('/admin/scripts/files')
                ->with('file_error', 'Nama file tidak valid.');
        }

        $filepath = APPPATH . 'Scripts' . DIRECTORY_SEPARATOR . $filename;
        if (!file_exists($filepath)) {
            return redirect()->to('/admin/scripts/files')
                ->with('file_error', "File <strong>{$filename}</strong> tidak ditemukan.");
        }

        // Derive slug & title dari nama file
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        $slug     = mb_url_title(str_replace(['_'], '-', $basename), '-', true);
        $title    = ucwords(str_replace(['-', '_'], ' ', $basename));

        // URL publik file
        $fileUrl    = base_url('scripts/' . $basename . '.ps1');
        $command    = "irm {$fileUrl} | iex";
        $commandCmd = "powershell -ExecutionPolicy Bypass -Command \"irm '{$fileUrl}' | iex\"";

        // Cek apakah slug sudah ada
        $existing = $this->scriptModel->findBySlug($slug);
        if ($existing) {
            return redirect()->to('/admin/scripts/' . $existing['id'] . '/edit')
                ->with('gen_warn', "Script dengan slug <code>{$slug}</code> sudah ada. Perbarui deskripsinya di bawah.");
        }

        // Generate UUID v4
        $id = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        $data = [
            'id'          => $id,
            'slug'        => $slug,
            'title'       => $title,
            'description' => '',
            'tag'         => 'powershell',
            'icon'        => 'tool',
            'icon_color'  => 'blue',
            'command'     => $command,
            'command_cmd' => $commandCmd,
            'sort_order'  => 0,
        ];

        try {
            $this->scriptModel->insert($data);
            return redirect()->to('/admin/scripts/' . $id . '/edit')
                ->with('gen_ok', "Script <strong>{$title}</strong> berhasil digenerate dari <code>{$filename}</code>. Lengkapi deskripsinya di bawah.");
        } catch (\Exception $e) {
            return redirect()->to('/admin/scripts/files')
                ->with('file_error', 'Gagal generate script: ' . $e->getMessage());
        }
    }

    public function deleteFile($filename)
    {
        // Proteksi path traversal
        if (str_contains($filename, '/') || str_contains($filename, '\\') || str_contains($filename, '..')) {
            return redirect()->to('/admin/scripts/files')
                ->with('file_error', 'Nama file tidak valid.');
        }

        $filepath = APPPATH . 'Scripts' . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filepath)) {
            return redirect()->to('/admin/scripts/files')
                ->with('file_error', "File <strong>{$filename}</strong> tidak ditemukan.");
        }

        unlink($filepath);
        return redirect()->to('/admin/scripts/files')
            ->with('file_msg', "File <strong>{$filename}</strong> berhasil dihapus.");
    }
}
