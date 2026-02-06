<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(session()->get('role') == 'owner' ? 'owner' : 'agency');
        }
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $session = session();
        $model = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // Simple validation rule
        if(!$this->validate([
            'username' => 'required',
            'password' => 'required'
        ])) {
            return view('auth/login', ['validation' => $this->validator]);
        }

        $user = $model->where('username', $username)->first();

        if ($user) {
            $pass = $user['password'];
            if (password_verify($password, $pass)) {
                $ses_data = [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'role'     => $user['role'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                return redirect()->to($user['role'] == 'owner' ? 'owner' : 'agency');
            } else {
                $session->setFlashdata('msg', 'Password Salah');
                return redirect()->to('login');
            }
        } else {
            $session->setFlashdata('msg', 'Username Tidak Ditemukan');
            return redirect()->to('login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
