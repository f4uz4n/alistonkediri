<?php

namespace App\Controllers;

use App\Models\UserModel;

class AgencyAdmin extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->userModel->where('role', 'agency');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('full_name', $search)
                ->orLike('username', $search)
                ->groupEnd();
        }

        if ($status !== '' && $status !== null) {
            $builder->where('is_active', $status);
        }

        $agencies = $builder->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'agencies' => $agencies,
            'search' => $search,
            'status' => $status
        ];
        return view('owner/agency/index', $data);
    }

    public function create()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        return view('owner/agency/create');
    }

    public function store()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'full_name' => 'required|min_length[3]',
            'phone' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Cek kembali isian Anda. Username mungkin sudah terdaftar atau isian kurang lengkap.');
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'role' => 'agency'
        ];

        // Handle Profile Picture
        $img = $this->request->getFile('profile_pic');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move('uploads/profiles', $newName);
            $data['profile_pic'] = 'uploads/profiles/' . $newName;
        }

        if ($this->userModel->insert($data)) {
            return redirect()->to('owner/agency')->with('msg', 'Agensi ' . $data['full_name'] . ' berhasil didaftarkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan agensi');
    }

    public function toggleStatus($id)
    {
        if (session()->get('role') != 'owner') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $agency = $this->userModel->find($id);
        if (!$agency) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Agensi tidak ditemukan']);
        }

        $newStatus = !$agency['is_active'];
        $this->userModel->update($id, ['is_active' => $newStatus]);

        return $this->response->setJSON(['status' => 'success', 'new_status' => $newStatus]);
    }

    public function edit($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $agency = $this->userModel->find($id);
        if (!$agency) {
            return redirect()->to('owner/agency')->with('error', 'Agensi tidak ditemukan');
        }

        $data = [
            'agency' => $agency
        ];
        return view('owner/agency/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,{$id}]",
            'full_name' => 'required|min_length[3]',
            'phone' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Cek kembali isian Anda.');
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ];

        // Optional password update
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Handle Profile Picture
        $img = $this->request->getFile('profile_pic');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move('uploads/profiles', $newName);
            $data['profile_pic'] = 'uploads/profiles/' . $newName;
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('owner/agency')->with('msg', 'Data agensi berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data agensi');
    }
}
