<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $data['user'] = $model->find(session()->get('id'));
        return view('profile/index', $data);
    }

    public function update()
    {
        $model = new UserModel();
        $id = session()->get('id');

        $rules = [
            'full_name' => 'required|min_length[3]',
            'email'     => 'required|valid_email',
            'phone'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal');
        }

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ];

        $file = $this->request->getFile('profile_pic');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/profiles', $newName);
            $data['profile_pic'] = 'uploads/profiles/' . $newName;
        }

        $model->update($id, $data);
        return redirect()->to('profile')->with('msg', 'Profil berhasil diperbarui');
    }

    public function changePassword()
    {
        $model = new UserModel();
        $id = session()->get('id');

        $rules = [
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Password tidak cocok atau terlalu pendek');
        }

        $model->update($id, [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
        ]);

        return redirect()->to('profile')->with('msg', 'Password berhasil diubah');
    }
}
