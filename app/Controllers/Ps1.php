<?php

namespace App\Controllers;

class Ps1 extends BaseController
{
    /**
     * Serve a PowerShell script by name.
     * Accessible via: GET /scripts/{name}.ps1
     * Usage  : irm http://yourdomain.com/scripts/{name}.ps1 | iex
     */
    public function serve(string $name): \CodeIgniter\HTTP\Response
    {
        $file = APPPATH . 'Scripts/' . $name . '.ps1';

        if (! is_file($file)) {
            return $this->response
                ->setStatusCode(404)
                ->setContentType('text/plain')
                ->setBody("# Script '$name.ps1' tidak ditemukan.");
        }

        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', 'text/plain; charset=utf-8')
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->setBody(file_get_contents($file));
    }
}
