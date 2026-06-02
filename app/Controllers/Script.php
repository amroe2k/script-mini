<?php

namespace App\Controllers;

use App\Models\ScriptModel;

class Script extends BaseController
{
    public function detail(string $slug): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $model  = new ScriptModel();
        $script = $model->findBySlug($slug);

        if (! $script) {
            return redirect()->to('/');
        }

        return view('script/detail', [
            'title'  => $script['title'] . ' | ScriptHub',
            'desc'   => $script['description'],
            'script' => $script,
        ]);
    }
}
