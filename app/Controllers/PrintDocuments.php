<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use App\Models\TravelSavingDepositModel;
use App\Models\TravelSavingModel;
use App\Models\UserModel;
use App\Models\PackageModel;

class PrintDocuments extends BaseController
{
    protected $participantModel;
    protected $depositModel;
    protected $savingModel;
    protected $userModel;
    protected $packageModel;

    public function __construct()
    {
        $this->participantModel = new ParticipantModel();
        $this->depositModel = new TravelSavingDepositModel();
        $this->savingModel = new TravelSavingModel();
        $this->userModel = new UserModel();
        $this->packageModel = new PackageModel();
    }

    /**
     * Cetak surat izin cuti untuk jamaah
     */
    public function printLeaveLetter()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $participantId = $this->request->getGet('participant_id');
        if (empty($participantId)) {
            return redirect()->to('owner/print-documents')->with('error', 'Pilih jamaah terlebih dahulu.');
        }

        $participant = $this->participantModel
            ->select('participants.*, travel_packages.name as package_name, travel_packages.departure_date as package_departure_date, travel_packages.duration, users.full_name as agency_name')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->join('users', 'users.id = participants.agency_id')
            ->where('participants.id', $participantId)
            ->first();

        if (!$participant) {
            return redirect()->to('owner/participant')->with('error', 'Data jamaah tidak ditemukan.');
        }

        // Ambil data owner untuk kop surat
        $owner = $this->userModel->where('role', 'owner')->first();
        $companyLogo = !empty($owner['company_logo']) ? base_url($owner['company_logo']) : base_url('assets/img/logo_.png');
        $companyName = $owner['company_name'] ?? 'Nama Perusahaan';
        $companyAddress = $owner['address'] ?? '';
        $namaDirektur = $owner['full_name'] ?? '—';

        // Hitung durasi perjalanan
        // Parse duration dari format seperti "9 Hari" atau "9" menjadi angka
        $durationStr = $participant['duration'] ?? '9';
        preg_match('/(\d+)/', $durationStr, $matches);
        $durationDays = !empty($matches[1]) ? (int)$matches[1] : 9;
        $departureDate = !empty($participant['package_departure_date']) ? strtotime($participant['package_departure_date']) : time();
        $returnDate = strtotime('+' . $durationDays . ' days', $departureDate);

        $data = [
            'participant' => $participant,
            'company_logo_url' => $companyLogo,
            'company_name' => $companyName,
            'company_address' => $companyAddress,
            'nama_direktur' => $namaDirektur,
            'departure_date' => $departureDate,
            'return_date' => $returnDate,
            'duration_days' => $durationDays,
        ];

        return view('owner/print/leave_letter', $data);
    }

    /**
     * Cetak bukti setoran tabungan
     */
    public function printDepositReceipt()
    {
        $role = session()->get('role');
        if (!in_array($role, ['owner', 'agency'])) {
            return redirect()->to('/login');
        }

        $depositId = $this->request->getGet('deposit_id');
        if (empty($depositId)) {
            $redirectUrl = $role === 'owner' ? 'owner/print-documents' : 'agency/tabungan';
            return redirect()->to($redirectUrl)->with('error', 'Pilih setoran terlebih dahulu.');
        }

        $deposit = $this->depositModel
            ->select('travel_savings_deposits.*, travel_savings.name as saving_name, travel_savings.nik as saving_nik, travel_savings.phone as saving_phone, travel_savings.agency_id, users.full_name as agency_name, users.nomor_rekening, users.nama_bank')
            ->join('travel_savings', 'travel_savings.id = travel_savings_deposits.travel_saving_id')
            ->join('users', 'users.id = travel_savings.agency_id')
            ->where('travel_savings_deposits.id', $depositId)
            ->first();

        if (!$deposit) {
            $redirectUrl = $role === 'owner' ? 'owner/tabungan' : 'agency/tabungan';
            return redirect()->to($redirectUrl)->with('error', 'Data setoran tidak ditemukan.');
        }

        // Validasi untuk agency: hanya bisa cetak setoran miliknya sendiri
        if ($role === 'agency') {
            $agencyId = session()->get('id');
            if ((int)$deposit['agency_id'] !== (int)$agencyId) {
                return redirect()->to('agency/tabungan')->with('error', 'Anda tidak memiliki akses untuk setoran ini.');
            }
        }

        // Hanya bisa cetak setoran yang sudah verified
        if (($deposit['status'] ?? 'pending') !== 'verified') {
            $redirectUrl = $role === 'owner' ? 'owner/tabungan' : 'agency/tabungan';
            return redirect()->to($redirectUrl)->with('error', 'Setoran belum diverifikasi.');
        }

        // Ambil data owner untuk kop surat
        $owner = $this->userModel->where('role', 'owner')->first();
        $companyLogo = !empty($owner['company_logo']) ? base_url($owner['company_logo']) : base_url('assets/img/logo_.png');
        $companyName = $owner['company_name'] ?? 'Nama Perusahaan';
        $companyAddress = $owner['address'] ?? '';
        $namaDirektur = $owner['full_name'] ?? '—';

        // Ambil total saldo tabungan
        $saving = $this->savingModel->find($deposit['travel_saving_id']);
        $totalBalance = $saving['total_balance'] ?? 0;

        $data = [
            'deposit' => $deposit,
            'total_balance' => $totalBalance,
            'company_logo_url' => $companyLogo,
            'company_name' => $companyName,
            'company_address' => $companyAddress,
            'nama_direktur' => $namaDirektur,
        ];

        return view('owner/print/deposit_receipt', $data);
    }

    /**
     * Halaman index untuk memilih dokumen yang akan dicetak
     */
    public function index()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        // Ambil daftar jamaah untuk dropdown surat izin cuti
        $participants = $this->participantModel
            ->select('participants.id, participants.name, participants.nik, travel_packages.name as package_name, travel_packages.departure_date')
            ->join('travel_packages', 'travel_packages.id = participants.package_id')
            ->where('participants.is_verified', 1)
            ->orderBy('participants.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Cetak Dokumen',
            'participants' => $participants,
        ];

        return view('owner/print/index', $data);
    }
}
