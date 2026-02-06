<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MaterialModel;
use App\Models\MemberModel;

class Agency extends BaseController
{
    public function index()
    {
        $materialModel = new MaterialModel();
        $participantModel = new \App\Models\ParticipantModel();
        $db = \Config\Database::connect();
        $agencyId = session()->get('id');

        // Stats
        $totalJamaah = $participantModel->where('agency_id', $agencyId)->countAllResults();
        $verifiedJamaah = $participantModel->where('agency_id', $agencyId)->where('status', 'verified')->countAllResults();
        
        // Calculate Income (Sum of package price for verified participants)
        $incomeQuery = $db->table('participants')
            ->selectSum('travel_packages.price', 'total_income')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->where('participants.agency_id', $agencyId)
            ->where('participants.status', 'verified')
            ->get()
            ->getRow();
            
        $totalIncome = $incomeQuery ? $incomeQuery->total_income : 0;

        // Recent Verified Jamaah
        $recentParticipants = $participantModel->select('participants.*, travel_packages.name as package_name')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->where('participants.agency_id', $agencyId)
            ->where('participants.status', 'verified')
            ->orderBy('participants.updated_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'materials' => $materialModel->orderBy('created_at', 'DESC')->findAll(),
            'stats' => [
                'total_jamaah' => $totalJamaah,
                'verified_jamaah' => $verifiedJamaah,
                'total_income' => $totalIncome
            ],
            'recent_participants' => $recentParticipants
        ];
        return view('agency/dashboard', $data);
    }

    public function income()
    {
        $participantModel = new \App\Models\ParticipantModel();
        $db = \Config\Database::connect();
        $agencyId = session()->get('id');

        // Verified Participants with Price
        $verifiedParticipants = $participantModel->select('participants.*, travel_packages.name as package_name, travel_packages.price')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->where('participants.agency_id', $agencyId)
            ->where('participants.status', 'verified')
            ->orderBy('participants.updated_at', 'DESC')
            ->findAll();

        $totalIncome = 0;
        foreach ($verifiedParticipants as $p) {
            $totalIncome += $p['price'];
        }

        $data = [
            'participants' => $verifiedParticipants,
            'total_income' => $totalIncome
        ];

        return view('agency/income', $data);
    }

    public function team()
    {
        $memberModel = new MemberModel();
        $data = [
            'members' => $memberModel->where('agency_id', session()->get('id'))->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('agency/team', $data);
    }

    public function addMember()
    {
        return view('agency/add_member');
    }

    public function storeMember()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'phone' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return view('agency/add_member', ['validation' => $this->validator]);
        }

        $memberModel = new MemberModel();
        $memberModel->save([
            'agency_id' => session()->get('id'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')
        ]);

        return redirect()->to('agency/team')->with('msg', 'Anggota berhasil ditambahkan');
    }

    public function packages()
    {
        $packageModel = new \App\Models\PackageModel();
        $data = [
            'packages' => $packageModel->findAll()
        ];
        return view('agency/packages', $data);
    }

    public function packageDetail($id)
    {
        $packageModel = new \App\Models\PackageModel();
        $package = $packageModel->find($id);

        if (!$package) {
            return redirect()->to('agency/packages')->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'package' => $package
        ];
        return view('agency/package_detail', $data);
    }

    public function register($package_id)
    {
        $packageModel = new \App\Models\PackageModel();
        $package = $packageModel->find($package_id);

        if (!$package) {
            return redirect()->to('agency/packages')->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'package' => $package
        ];
        return view('agency/register', $data);
    }

    public function storeRegistration()
    {
        $rules = [
            'package_id' => 'required',
            'nik' => 'required|min_length[16]|max_length[20]',
            'name' => 'required|min_length[3]',
            'place_of_birth' => 'required',
            'date_of_birth' => 'required|valid_date',
            'gender' => 'required',
            'phone' => 'required|min_length[8]',
            'address' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi seluruh biodata wajib sesuai KTP.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $participantModel = new \App\Models\ParticipantModel();
        $participantData = [
            'package_id' => $this->request->getPost('package_id'),
            'agency_id' => session()->get('id'),
            'nik' => $this->request->getPost('nik'),
            'name' => $this->request->getPost('name'),
            'place_of_birth' => $this->request->getPost('place_of_birth'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'rt_rw' => $this->request->getPost('rt_rw'),
            'kelurahan' => $this->request->getPost('kelurahan'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'kabupaten' => $this->request->getPost('kabupaten'),
            'provinsi' => $this->request->getPost('provinsi'),
            'religion' => $this->request->getPost('religion'),
            'marital_status' => $this->request->getPost('marital_status'),
            'occupation' => $this->request->getPost('occupation'),
            'blood_type' => $this->request->getPost('blood_type'),
            'nationality' => $this->request->getPost('nationality') ?? 'WNI',
            'phone' => $this->request->getPost('phone'),
            'status' => 'pending'
        ];

        $participantId = $participantModel->insert($participantData);

        // Handle Document Uploads
        $docTypes = ['passport', 'id_card', 'vaccine'];
        foreach ($docTypes as $type) {
            $file = $this->request->getFile($type);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/documents', $newName);
                $db->table('participant_documents')->insert([
                    'participant_id' => $participantId,
                    'type' => $type,
                    'file_path' => 'uploads/documents/' . $newName,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pendaftaran.');
        }

        return redirect()->to('agency/packages')->with('msg', 'Pendaftaran jamaah ' . $participantData['name'] . ' berhasil! Mohon tunggu verifikasi berkas.');
    }

    public function participants()
    {
        $participantModel = new \App\Models\ParticipantModel();
        
        $keyword = $this->request->getGet('search');
        $builder = $participantModel->select('participants.*, travel_packages.name as package_name, travel_packages.freebies')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->where('participants.agency_id', session()->get('id'));

        if ($keyword) {
            $builder->groupStart()
                ->like('participants.name', $keyword)
                ->orLike('participants.nik', $keyword)
            ->groupEnd();
        }

        $participants = $builder->orderBy('participants.created_at', 'DESC')->findAll();

        $db = \Config\Database::connect();
        $equipmentModel = new \App\Models\EquipmentModel();

        foreach ($participants as &$p) {
            $p['doc_count'] = $db->table('participant_documents')->where('participant_id', $p['id'])->countAllResults();
            $p['doc_total'] = 3;
            $freebies = json_decode($p['freebies'], true) ?? [];
            $p['equip_total'] = count($freebies);
            $p['equip_count'] = $equipmentModel->where('participant_id', $p['id'])->where('status', 'collected')->countAllResults();
        }

        $data = [
            'participants' => $participants,
            'keyword' => $keyword
        ];
        return view('agency/participants', $data);
    }

    public function payments()
    {
        $participantModel = new \App\Models\ParticipantModel();
        $keyword = $this->request->getGet('search');

        $builder = $participantModel->select('participants.id, participants.name, participants.nik, travel_packages.name as package_name, travel_packages.price')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->where('participants.agency_id', session()->get('id'));

        if ($keyword) {
            $builder->groupStart()
                ->like('participants.name', $keyword)
                ->orLike('participants.nik', $keyword)
            ->groupEnd();
        }

        $data = [
            'participants' => $builder->findAll(),
            'keyword' => $keyword
        ];

        $paymentModel = new \App\Models\PaymentModel();
        foreach ($data['participants'] as &$p) {
            $paid = $paymentModel->getTotalPaid($p['id']);
            $p['total_paid'] = $paid['amount'] ?? 0;
            $p['remaining'] = (float)$p['price'] - (float)$p['total_paid'];
        }

        return view('agency/payments', $data);
    }

    public function paymentDetail($participant_id)
    {
        $participantModel = new \App\Models\ParticipantModel();
        $participant = $participantModel->select('participants.*, travel_packages.name as package_name, travel_packages.price')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->find($participant_id);

        if (!$participant || $participant['agency_id'] != session()->get('id')) {
            return redirect()->to('agency/payments')->with('error', 'Jamaah tidak ditemukan');
        }

        $paymentModel = new \App\Models\PaymentModel();
        $data = [
            'participant' => $participant,
            'installments' => $paymentModel->getInstallments($participant_id),
            'total_paid' => $paymentModel->getTotalPaid($participant_id)['amount'] ?? 0
        ];

        return view('agency/payment_detail', $data);
    }

    public function storePayment()
    {
        $rules = [
            'participant_id' => 'required',
            'amount'         => 'required|numeric',
            'payment_date'   => 'required|valid_date',
            'proof'          => 'uploaded[proof]|max_size[proof,4096]|is_image[proof]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi data pembayaran dengan benar.');
        }

        $file = $this->request->getFile('proof');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/payments', $newName);

            $paymentModel = new \App\Models\PaymentModel();
            $paymentModel->insert([
                'participant_id' => $this->request->getPost('participant_id'),
                'amount'         => $this->request->getPost('amount'),
                'payment_date'   => $this->request->getPost('payment_date'),
                'notes'          => $this->request->getPost('notes'),
                'proof'          => 'uploads/payments/' . $newName,
                'status'         => 'pending'
            ]);

            return redirect()->back()->with('msg', 'Bukti pembayaran berhasil diunggah dan sedang diverifikasi.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }
    public function editParticipant($id)
    {
        $participantModel = new \App\Models\ParticipantModel();
        $participant = $participantModel->find($id);

        if (!$participant || $participant['agency_id'] != session()->get('id')) {
            return redirect()->to('agency/participants')->with('error', 'Jamaah tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $documents = $db->table('participant_documents')
            ->where('participant_id', $id)
            ->get()
            ->getResultArray();

        $docsFormatted = [];
        foreach ($documents as $doc) {
            $docsFormatted[$doc['type']] = $doc['file_path'];
        }

        $packageModel = new \App\Models\PackageModel();
        $data = [
            'participant' => $participant,
            'package' => $packageModel->find($participant['package_id']),
            'documents' => $docsFormatted
        ];
        return view('agency/edit_participant', $data);
    }

    public function updateParticipant($id)
    {
        $participantModel = new \App\Models\ParticipantModel();
        $participant = $participantModel->find($id);

        if (!$participant || $participant['agency_id'] != session()->get('id')) {
            return redirect()->to('agency/participants')->with('error', 'Jamaah tidak ditemukan');
        }

        $rules = [
            'nik' => 'required|min_length[16]|max_length[20]',
            'name' => 'required|min_length[3]',
            'phone' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi data wajib dengan benar.');
        }

        $participantData = $this->request->getPost();
        unset($participantData['id']); // Safety

        $db = \Config\Database::connect();
        $db->transStart();

        $participantModel->update($id, $participantData);

        // Handle Document Revisions
        $docTypes = ['passport', 'id_card', 'vaccine'];
        foreach ($docTypes as $type) {
            $file = $this->request->getFile($type);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/documents', $newName);

                // Check if document already exists
                $existingDoc = $db->table('participant_documents')
                    ->where('participant_id', $id)
                    ->where('type', $type)
                    ->get()
                    ->getRow();

                if ($existingDoc) {
                    // Update
                    $db->table('participant_documents')
                        ->where('id', $existingDoc->id)
                        ->update([
                            'file_path' => 'uploads/documents/' . $newName,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    
                    // Optional: delete old file safely
                    $oldPath = $existingDoc->file_path;
                    if (!empty($oldPath) && file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                } else {
                    // Insert
                    $db->table('participant_documents')->insert([
                        'participant_id' => $id,
                        'type' => $type,
                        'file_path' => 'uploads/documents/' . $newName,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data jamaah.');
        }

        return redirect()->to('agency/participants')->with('msg', 'Data jamaah ' . $participant['name'] . ' berhasil diperbarui.');
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
            return redirect()->to('agency/participants')->with('error', 'Jamaah tidak ditemukan.');
        }

        $documents = $db->table('participant_documents')->where('participant_id', $id)->get()->getResultArray();
        $docsFormatted = [];
        foreach ($documents as $doc) {
            $docsFormatted[$doc['type']] = $doc['file_path'];
        }

        // Calculate document progress
        $total_goal = 3; // passport, id_card, vaccine
        $verified_count = $db->table('participant_documents')
            ->where('participant_id', $id)
            ->where('status', 'verified')
            ->countAllResults();
        $doc_progress = $total_goal > 0 ? round(($verified_count / $total_goal) * 100) : 0;


        $collectedEquipment = $equipmentModel->getByParticipant($id);
        $collectedMap = [];
        foreach ($collectedEquipment as $eq) {
            $collectedMap[$eq['item_name']] = $eq;
        }

        $freebies = json_decode($participant['freebies'], true) ?? [];

        return view('agency/checklist', [
            'title' => 'Cek List & Kelengkapan - ' . $participant['name'],
            'participant' => $participant,
            'documents' => $docsFormatted,
            'freebies' => $freebies,
            'collectedMap' => $collectedMap,
            'doc_progress' => $doc_progress,
            'verified_count' => $verified_count,
            'total_goal' => $total_goal
        ]);
    }

    public function toggleEquipment()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id = $this->request->getPost('participant_id');
        $itemName = $this->request->getPost('item_name');
        $status = $this->request->getPost('status');

        $equipmentModel = new \App\Models\EquipmentModel();
        $existing = $equipmentModel->where('participant_id', $id)->where('item_name', $itemName)->first();

        if ($existing) {
            $equipmentModel->update($existing['id'], [
                'status' => $status,
                'collected_at' => ($status === 'collected') ? date('Y-m-d H:i:s') : null,
                'collected_by' => ($status === 'collected') ? session()->get('user_id') : null
            ]);
        } else {
            $equipmentModel->insert([
                'participant_id' => $id,
                'item_name' => $itemName,
                'status' => $status,
                'collected_at' => ($status === 'collected') ? date('Y-m-d H:i:s') : null,
                'collected_by' => ($status === 'collected') ? session()->get('user_id') : null
            ]);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Status kelengkapan diperbarui']);
    }
}
