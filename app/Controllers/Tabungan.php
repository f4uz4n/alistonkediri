<?php

namespace App\Controllers;

use App\Models\TravelSavingModel;
use App\Models\TravelSavingDepositModel;
use App\Models\UserModel;
use App\Models\PackageModel;
use App\Models\ParticipantModel;
use App\Models\PaymentModel;

class Tabungan extends BaseController
{
    protected $savingModel;
    protected $depositModel;
    protected $userModel;
    protected $packageModel;
    protected $participantModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->savingModel = new TravelSavingModel();
        $this->depositModel = new TravelSavingDepositModel();
        $this->userModel = new UserModel();
        $this->packageModel = new PackageModel();
        $this->participantModel = new ParticipantModel();
        $this->paymentModel = new PaymentModel();
    }

    public function index()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $status = $this->request->getGet('status');
        $builder = $this->savingModel->getWithAgency();
        if ($status !== null && $status !== '') {
            $builder->where('travel_savings.status', $status);
        }
        $savings = $builder->findAll();

        $data = [
            'savings' => $savings,
            'filterStatus' => $status,
            'title' => 'Tabungan Perjalanan',
        ];
        return view('owner/tabungan/index', $data);
    }

    public function create()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $agencies = $this->userModel->where('role', 'agency')->where('is_active', 1)->findAll();
        $kantor = $this->userModel->where('username', 'kantor_pusat')->first();
        if ($kantor) {
            $agencies = array_merge([$kantor], $agencies);
        }
        $data = [
            'agencies' => $agencies,
            'title' => 'Tambah Jamaah Tabungan',
        ];
        return view('owner/tabungan/create', $data);
    }

    public function store()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $rules = [
            'agency_id' => 'required|integer',
            'name' => 'required|min_length[3]',
            'nik' => 'required|min_length[16]|max_length[20]',
            'phone' => 'permit_empty',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'agency_id' => (int) $this->request->getPost('agency_id'),
            'name' => $this->request->getPost('name'),
            'nik' => $this->request->getPost('nik'),
            'phone' => $this->request->getPost('phone') ?: null,
            'total_balance' => 0,
            'status' => 'menabung',
            'notes' => $this->request->getPost('notes') ?: null,
        ];
        if ($this->savingModel->insert($data)) {
            return redirect()->to('owner/tabungan')->with('msg', 'Jamaah tabungan berhasil didaftarkan.');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
    }

    public function addDeposit($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $saving = $this->savingModel->find($id);
        if (!$saving || $saving['status'] !== 'menabung') {
            return redirect()->to('owner/tabungan')->with('error', 'Data tabungan tidak ditemukan atau sudah diklaim.');
        }
        $saving['agency_name'] = $this->userModel->find($saving['agency_id'])['full_name'] ?? '-';
        $deposits = $this->depositModel->getBySaving($id);
        $data = ['saving' => $saving, 'deposits' => $deposits, 'title' => 'Tambah Setoran'];
        return view('owner/tabungan/deposit', $data);
    }

    public function storeDeposit()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $travelSavingId = (int) $this->request->getPost('travel_saving_id');
        $saving = $this->savingModel->find($travelSavingId);
        if (!$saving || $saving['status'] !== 'menabung') {
            return redirect()->to('owner/tabungan')->with('error', 'Data tabungan tidak ditemukan atau sudah diklaim.');
        }

        $rules = [
            'amount' => 'required|decimal|greater_than[0]',
            'payment_date' => 'required|valid_date',
        ];
        if (!$this->validate($rules)) {
            return redirect()->to("owner/tabungan/deposit/{$travelSavingId}")->withInput()->with('errors', $this->validator->getErrors());
        }

        $amount = (float) str_replace(',', '', $this->request->getPost('amount'));
        $proof = null;
        $file = $this->request->getFile('proof');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $dir = FCPATH . 'uploads/tabungan';
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $proof = 'uploads/tabungan/' . $file->getRandomName();
            $file->move($dir, basename($proof));
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->depositModel->insert([
            'travel_saving_id' => $travelSavingId,
            'amount' => $amount,
            'payment_date' => $this->request->getPost('payment_date'),
            'proof' => $proof,
            'status' => 'verified',
            'notes' => $this->request->getPost('notes') ?: null,
        ]);
        $newTotal = $this->depositModel->getTotalVerified($travelSavingId);
        $this->savingModel->update($travelSavingId, ['total_balance' => $newTotal]);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to("owner/tabungan/deposit/{$travelSavingId}")->with('error', 'Gagal menyimpan setoran.');
        }
        return redirect()->to("owner/tabungan/deposit/{$travelSavingId}")->with('msg', 'Setoran berhasil ditambahkan.');
    }

    public function claimForm($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $saving = $this->savingModel->find($id);
        if (!$saving || $saving['status'] !== 'menabung') {
            return redirect()->to('owner/tabungan')->with('error', 'Data tabungan tidak ditemukan atau sudah diklaim.');
        }
        $balance = (float) $saving['total_balance'];
        $packages = $this->packageModel->orderBy('departure_date', 'DESC')->findAll();
        $saving['agency_name'] = $this->userModel->find($saving['agency_id'])['full_name'] ?? '-';
        $data = [
            'saving' => $saving,
            'packages' => $packages,
            'totalBalance' => $balance,
            'title' => 'Klaim Tabungan ke Paket',
        ];
        return view('owner/tabungan/claim', $data);
    }

    public function doClaim()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $travelSavingId = (int) $this->request->getPost('travel_saving_id');
        $packageId = (int) $this->request->getPost('package_id');
        $saving = $this->savingModel->find($travelSavingId);
        if (!$saving || $saving['status'] !== 'menabung') {
            return redirect()->to('owner/tabungan')->with('error', 'Data tabungan tidak ditemukan atau sudah diklaim.');
        }
        $package = $this->packageModel->find($packageId);
        if (!$package) {
            return redirect()->back()->with('error', 'Paket tidak ditemukan.');
        }

        $totalBalance = (float) $saving['total_balance'];
        if ($totalBalance <= 0) {
            return redirect()->back()->with('error', 'Saldo tabungan tidak cukup.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $participantData = [
            'package_id' => $packageId,
            'agency_id' => $saving['agency_id'],
            'nik' => $saving['nik'],
            'name' => $saving['name'],
            'phone' => $saving['phone'] ?? null,
            'status' => 'pending',
            'place_of_birth' => null,
            'date_of_birth' => null,
            'gender' => null,
            'address' => null,
            'religion' => null,
            'marital_status' => null,
            'occupation' => null,
        ];
        $this->participantModel->insert($participantData);
        $participantId = $this->participantModel->getInsertID();

        $this->paymentModel->insert([
            'participant_id' => $participantId,
            'amount' => $totalBalance,
            'payment_date' => date('Y-m-d'),
            'proof' => null,
            'status' => 'verified',
            'notes' => 'Dari tabungan perjalanan #' . $travelSavingId,
        ]);

        $this->savingModel->update($travelSavingId, [
            'status' => 'claimed',
            'package_id' => $packageId,
            'participant_id' => $participantId,
            'claimed_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('owner/tabungan')->with('error', 'Gagal mengklaim tabungan.');
        }
        return redirect()->to('owner/tabungan')->with('msg', 'Tabungan berhasil diklaim. Jamaah telah didaftarkan ke paket.');
    }
}
