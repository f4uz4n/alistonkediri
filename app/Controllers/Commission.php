<?php

namespace App\Controllers;

use App\Models\AgencyCommissionModel;
use App\Models\ParticipantModel;
use App\Models\PackageModel;
use App\Models\UserModel;

class Commission extends BaseController
{
    protected $commissionModel;
    protected $participantModel;
    protected $packageModel;
    protected $userModel;

    public function __construct()
    {
        $this->commissionModel = new AgencyCommissionModel();
        $this->participantModel = new ParticipantModel();
        $this->packageModel = new PackageModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (session()->get('role') != 'owner')
            return redirect()->to('/login');

        // Refresh calculations before showing
        $this->syncCommissions();

        $filters = [
            'search' => $this->request->getGet('search'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'package_id' => $this->request->getGet('package_id'),
            'departure_date' => $this->request->getGet('departure_date'),
        ];

        $summaryByDeparture = $this->commissionModel->getSummaryByDepartureDate();
        $pendingByDeparture = [];
        $commissionsByDeparture = [];
        foreach ($summaryByDeparture as $sum) {
            $d = $sum['departure_date'];
            if ((int)($sum['pending_count'] ?? 0) > 0) {
                $pendingByDeparture[$d] = $this->commissionModel->getPendingByDepartureDate($d);
            }
            $commissionsByDeparture[$d] = $this->commissionModel->getCommissionsByDepartureDate($d);
        }

        $data = [
            'commissions' => $this->commissionModel->getFilteredCommissions($filters),
            'packages' => $this->packageModel->findAll(),
            'departure_dates' => $this->commissionModel->getDepartureDatesWithCommissions(),
            'summary_by_departure' => $summaryByDeparture,
            'pending_by_departure' => $pendingByDeparture,
            'commissions_by_departure' => $commissionsByDeparture,
            'title' => 'Komisi Agensi',
            'search' => $filters['search'],
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
            'package_id' => $filters['package_id'],
            'departure_date' => $filters['departure_date'],
        ];

        return view('owner/commission/index', $data);
    }

    /**
     * Verifikasi komisi secara bulk per tanggal pemberangkatan.
     */
    public function verifyByDeparture()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login')->with('error', 'Unauthorized');
        }

        $departureDate = $this->request->getPost('departure_date');
        $notes = $this->request->getPost('notes');
        $ids = $this->request->getPost('commission_ids'); // array of id to mark paid

        if (empty($departureDate)) {
            return redirect()->back()->with('error', 'Pilih tanggal pemberangkatan.');
        }

        $pending = $this->commissionModel->getPendingByDepartureDate($departureDate);
        if (empty($pending)) {
            return redirect()->back()->with('error', 'Tidak ada komisi pending untuk jadwal ' . date('d/m/Y', strtotime($departureDate)) . '.');
        }

        $ids = is_array($ids) ? $ids : [];
        $now = date('Y-m-d H:i:s');
        $count = 0;
        foreach ($pending as $row) {
            $id = $row['id'];
            if (! empty($ids) && ! in_array((string) $id, array_map('strval', $ids), true)) {
                continue;
            }
            $this->commissionModel->update($id, [
                'status' => 'paid',
                'paid_at' => $now,
                'notes' => $notes ?: $row['notes'],
            ]);
            $count++;
        }

        return redirect()->back()->with('msg', $count . ' komisi untuk jadwal ' . date('d/m/Y', strtotime($departureDate)) . ' ditandai sudah dibayar.');
    }

    /**
     * Cetak slip komisi (sudah terbayar) dengan QR code, tanggal penyerahan, nama pemilik tour.
     */
    public function printSlip($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $commission = $this->commissionModel
            ->select('agency_commissions.*, users.full_name as agency_name, travel_packages.name as package_name, travel_packages.departure_date')
            ->join('users', 'users.id = agency_commissions.agency_id')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id')
            ->where('agency_commissions.id', $id)
            ->first();

        if (!$commission || ($commission['status'] ?? '') !== 'paid') {
            return redirect()->to('owner/commissions')->with('error', 'Komisi tidak ditemukan atau belum berstatus dibayar.');
        }

        $owner = $this->userModel->find(session()->get('id'));
        $namaDirektur = $owner['full_name'] ?? session()->get('username') ?? '—';
        $namaPt = $owner['company_name'] ?? '';
        $companyLogo = !empty($owner['company_logo']) ? base_url($owner['company_logo']) : base_url('assets/img/logo_.png');

        $tanggalPenyerahan = !empty($commission['paid_at'])
            ? date('d F Y', strtotime($commission['paid_at']))
            : date('d F Y');

        $qrData = 'KOMISI#' . $id . '#' . ($commission['paid_at'] ?? '') . '#' . $commission['amount_final'];
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . rawurlencode($qrData);

        $alamatPt = $owner['address'] ?? '';

        $data = [
            'commission' => $commission,
            'nama_direktur' => $namaDirektur,
            'nama_pt' => $namaPt,
            'alamat_pt' => $alamatPt,
            'company_logo_url' => $companyLogo,
            'tanggal_penyerahan' => $tanggalPenyerahan,
            'tanggal_signature' => !empty($commission['paid_at']) ? date('d/m/Y H:i', strtotime($commission['paid_at'])) : date('d/m/Y H:i'),
            'qr_url' => $qrUrl,
        ];

        return view('owner/commission/print_slip', $data);
    }

    public function updateProgress($id)
    {
        if (session()->get('role') != 'owner')
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $amountFinal = $this->request->getPost('amount_final');
        $notes = $this->request->getPost('notes');
        $status = $this->request->getPost('status'); // paid or pending

        $updateData = [
            'amount_final' => $amountFinal,
            'notes' => $notes,
            'status' => $status
        ];

        if ($status == 'paid') {
            $updateData['paid_at'] = date('Y-m-d H:i:s');
        }
        else {
            $updateData['paid_at'] = null;
        }

        if (!$this->commissionModel->find($id)) {
            return redirect()->back()->with('error', 'Data komisi tidak ditemukan.');
        }

        $this->commissionModel->update($id, $updateData);

        return redirect()->back()->with('msg', 'Data komisi berhasil diperbarui.');
    }

    private function syncCommissions()
    {
        // Get all unique pairs of agency_id and package_id from verified participants
        $db = \Config\Database::connect();
        $builder = $db->table('participants');
        $builder->select('agency_id, package_id, COUNT(id) as total_pax');
        $builder->where('is_verified', 1);
        $builder->groupBy(['agency_id', 'package_id']);
        $pairs = $builder->get()->getResultArray();

        foreach ($pairs as $pair) {
            $package = $this->packageModel->find($pair['package_id']);
            if (!$package)
                continue;

            $rate = $package['commission_per_pax'];
            $calculated = $pair['total_pax'] * $rate;

            // Check if record exists
            $existing = $this->commissionModel->where([
                'agency_id' => $pair['agency_id'],
                'package_id' => $pair['package_id']
            ])->first();

            if ($existing) {
                $updateData = ['amount_calculated' => $calculated];

                // Only update final amount if it hasn't been manually overridden 
                // (i.e., it still matches the system calculation)
                if ($existing['status'] == 'pending' && $existing['amount_final'] == $existing['amount_calculated']) {
                    $updateData['amount_final'] = $calculated;
                }

                $this->commissionModel->update($existing['id'], $updateData);
            }
            else {
                // Create new
                $this->commissionModel->insert([
                    'agency_id' => $pair['agency_id'],
                    'package_id' => $pair['package_id'],
                    'amount_calculated' => $calculated,
                    'amount_final' => $calculated,
                    'status' => 'pending'
                ]);
            }
        }
    }
}
