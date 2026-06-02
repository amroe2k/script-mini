<?php

namespace App\Controllers;

use App\Models\ScriptModel;

class Home extends BaseController
{
    public function index(): string
    {
        $model   = new ScriptModel();
        $scripts = $model->getAllSorted();

        return view('home/index', [
            'title'   => 'ScriptHub | Pusat Automasi Skrip Server',
            'scripts' => $scripts,
        ]);
    }
}
