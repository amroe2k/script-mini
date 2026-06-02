<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        return redirect()->to('/admin/scripts');
    }

    public function login()
    {
        if (session()->get('admin_id')) {
            return redirect()->to('/admin/scripts');
        }

        return view('admin/login');
    }

    public function processLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        $builder = $db->table('admin_users');
        $user = $builder->where('username', $username)->get()->getRowArray();

        if ($user && password_verify($password, $user['password_hash'])) {
            session()->set('admin_id', $user['id']);
            return redirect()->to('/admin/scripts');
        }

        return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->remove('admin_id');
        return redirect()->to('/admin/login');
    }
}
