<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MaterialModel;
use App\Models\UserModel;
use App\Models\ParticipantModel;
use App\Models\PackageModel;

class Owner extends BaseController
{
    public function index()
    {
        $materialModel = new MaterialModel();
        $userModel = new UserModel();
        $participantModel = new \App\Models\ParticipantModel();
        $packageModel = new \App\Models\PackageModel();

        // Get Filter Parameters
        $filters = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'agency_id' => $this->request->getGet('agency_id'),
            'package_id' => $this->request->getGet('package_id'),
        ];

        // 1. Stats Calculation
        $registrations = $participantModel->getFilteredParticipants($filters) ?? [];

        $stats = [
            'total_jamaah' => count($registrations),
            'total_agencies' => $userModel->where('role', 'agency')->countAllResults(),
            'total_packages' => $packageModel->countAllResults(),
            'verified_jamaah' => 0,
            'pending_jamaah' => 0,
        ];

        foreach ($registrations as $reg) {
            if ($reg['status'] == 'verified')
                $stats['verified_jamaah']++;
            else
                $stats['pending_jamaah']++;
        }

        // 2. Chart Data: Trends (Last 30 Days if no date filter, otherwise filtered)
        $registrationTrends = $participantModel->getRegistrationTrends() ?? [];

        // 3. Agency Distribution
        $agencyPerformance = $participantModel->select('users.full_name, COUNT(participants.id) as total')
            ->join('users', 'users.id = participants.agency_id')
            ->groupBy('participants.agency_id')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->findAll() ?? [];

        // 4. Package distribution
        $packageDistribution = $participantModel->select('travel_packages.name, COUNT(participants.id) as total')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->groupBy('participants.package_id')
            ->orderBy('total', 'DESC')
            ->findAll() ?? [];

        $data = [
            'filters' => $filters,
            'stats' => $stats,
            'registrations' => $registrations,
            'trends' => $registrationTrends,
            'agencies_list' => $userModel->where('role', 'agency')->findAll() ?? [],
            'packages_list' => $packageModel->findAll() ?? [],
            'agency_perf' => $agencyPerformance,
            'package_dist' => $packageDistribution,
            'materials_count' => $materialModel->countAllResults()
        ];

        return view('owner/dashboard', $data);
    }

    public function materials()
    {
        $materialModel = new MaterialModel();
        $data = [
            'materials' => $materialModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('owner/materials/index', $data);
    }

    public function uploadMaterial()
    {
        return view('owner/materials/create');
    }

    public function storeMaterial()
    {
        $type = $this->request->getPost('type');
        $rules = [
            'type' => 'required|in_list[file,youtube,url]',
            'title' => 'required|min_length[3]|max_length[255]',
        ];

        if ($type == 'file') {
            $rules['attachment'] = [
                'uploaded[attachment]',
                'mime_in[attachment,application/pdf,image/jpg,image/jpeg,image/png]',
                'max_size[attachment,4096]', // 4MB
            ];
        }
        else {
            $rules['url'] = 'required|valid_url';
        }

        if (!$this->validate($rules)) {
            return view('owner/upload_material', ['validation' => $this->validator]);
        }

        $data = [
            'type' => $type,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'created_by' => session()->get('id')
        ];

        if ($type == 'file') {
            $file = $this->request->getFile('attachment');
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/uploads', $newName);
                $data['file_path'] = 'uploads/' . $newName;
            }
            else {
                return redirect()->back()->with('error', $file->getErrorString());
            }
        }
        else {
            $data['url'] = $this->request->getPost('url');
        }

        $materialModel = new MaterialModel();
        $materialModel->save($data);

        return redirect()->to('owner/materials')->with('msg', 'Materi berhasil diupload');
    }

    public function paymentVerification()
    {
        $paymentModel = new \App\Models\PaymentModel();
        
        $tab = $this->request->getGet('tab') ?? 'pending';
        $search = $this->request->getGet('search');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        $builder = $paymentModel->select('participant_payments.*, participants.name as participant_name, participants.nik as participant_nik, users.full_name as agency_name, travel_packages.name as package_name, travel_packages.price as package_price')
            ->join('participants', 'participants.id = participant_payments.participant_id')
            ->join('users', 'users.id = participants.agency_id')
            ->join('travel_packages', 'travel_packages.id = participants.package_id');

        // Apply Search Filters
        if ($search) {
            $builder->groupStart()
                ->like('participants.name', $search)
                ->orLike('participants.nik', $search)
                ->orLike('users.full_name', $search)
                ->orLike('travel_packages.name', $search)
                ->groupEnd();
        }

        // Apply Date Filters
        if ($startDate) {
            $builder->where('DATE(participant_payments.payment_date) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(participant_payments.payment_date) <=', $endDate);
        }
            
        if ($tab === 'history') {
            $builder->whereIn('participant_payments.status', ['verified', 'rejected']);
            $builder->orderBy('participant_payments.updated_at', 'DESC');
        } else {
            $builder->where('participant_payments.status', 'pending');
            $builder->orderBy('participant_payments.created_at', 'ASC');
        }
            
        $payments = $builder->findAll();

        // Enrich data with payment progress
        foreach ($payments as &$p) {
            $paid = $paymentModel->getTotalPaid($p['participant_id']);
            $p['total_paid_verified'] = $paid['amount'] ?? 0;
            $p['remaining_balance'] = (float)$p['package_price'] - (float)$p['total_paid_verified'];
            
            // Calculate percentage
            if ($p['package_price'] > 0) {
                $p['progress_percentage'] = min(100, round(($p['total_paid_verified'] / $p['package_price']) * 100));
            } else {
                $p['progress_percentage'] = 0;
            }
        }

        $data = [
            'payments' => $payments,
            'active_tab' => $tab,
            'filters' => [
                'search' => $search,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];

        return view('owner/payment_verification', $data);
    }

    public function verifyPayment()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $notes = $this->request->getPost('notes');

        $paymentModel = new \App\Models\PaymentModel();
        
        // Use Transaction to handle double verification prevention or related side effects if needed in future
        $paymentModel->update($id, [
            'status' => $status,
            'notes' => $notes
        ]);

        $msg = ($status == 'verified') ? 'Pembayaran berhasil diverifikasi.' : 'Pembayaran telah ditolak.';
        return redirect()->to('owner/payment-verification')->with('msg', $msg);
    }

    public function verifyParticipant()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status'); // 'verified' or 'pending'

        $participantModel = new \App\Models\ParticipantModel();
        $participantModel->update($id, [
            'is_verified' => ($status === 'verified'),
            'verified_at' => ($status === 'verified') ? date('Y-m-d H:i:s') : null,
            'verified_by' => ($status === 'verified') ? session()->get('id') : null,
            'status' => ($status === 'verified') ? 'verified' : 'pending' // Sync status
        ]);

        return redirect()->back()->with('msg', 'Status verifikasi jamaah diperbarui.');
    }

    public function checklist($id)
    {
        $participantModel = new \App\Models\ParticipantModel();
        $equipmentModel = new \App\Models\EquipmentModel();
        $db = \Config\Database::connect();

        $participant = $participantModel->select('participants.*, travel_packages.name as package_name, travel_packages.freebies, travel_packages.price')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->where('participants.id', $id)
            ->first();

        if (!$participant) {
            return redirect()->to('owner')->with('error', 'Jamaah tidak ditemukan.');
        }

        // Get documents
        $documents = $db->table('participant_documents')
            ->where('participant_id', $id)
            ->get()
            ->getResultArray();

        $docsFormatted = [];
        foreach ($documents as $doc) {
            $docsFormatted[$doc['type']] = $doc['file_path'];
        }

        // Get equipment status
        $collectedEquipment = $equipmentModel->getByParticipant($id);
        $collectedMap = [];
        foreach ($collectedEquipment as $eq) {
            $collectedMap[$eq['item_name']] = $eq;
        }

        // Prepare freebies list from package
        $freebies = json_decode($participant['freebies'], true) ?? [];

        return view('agency/checklist', [
            'title' => 'Verifikasi Jamaah - ' . $participant['name'],
            'participant' => $participant,
            'documents' => $docsFormatted,
            'freebies' => $freebies,
            'collectedMap' => $collectedMap,
            'is_owner' => true
        ]);
    }    public function settings()
    {
        $userModel = new \App\Models\UserModel();
        $data = [
            'user' => $userModel->find(session()->get('id'))
        ];
        return view('owner/settings', $data);
    }

    public function updateSettings()
    {
        $userModel = new \App\Models\UserModel();
        $id = session()->get('id');

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

        // Handle Company Logo
        $logo = $this->request->getFile('company_logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move('uploads/logos', $newName);
            $data['company_logo'] = 'uploads/logos/' . $newName;
        }

        if ($userModel->update($id, $data)) {
            return redirect()->to('owner/settings')->with('msg', 'Pengaturan berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengaturan');
    }
}
