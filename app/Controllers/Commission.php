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
        ];

        $data = [
            'commissions' => $this->commissionModel->getFilteredCommissions($filters),
            'packages' => $this->packageModel->findAll(),
            'title' => 'Komisi Agensi',
            'search' => $filters['search'],
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
            'package_id' => $filters['package_id'],
        ];

        return view('owner/commission/index', $data);
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
