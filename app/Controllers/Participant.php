<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use App\Models\PackageModel;
use App\Models\DocumentModel;
use App\Models\PaymentModel;

class Participant extends BaseController
{
    protected $participantModel;

    public function __construct()
    {
        $this->participantModel = new ParticipantModel();
    }

    private function getDocStats($participantId)
    {
        $docModel = new DocumentModel();
        $docs = $docModel->where('participant_id', $participantId)->where('is_verified', 1)->countAllResults();

        $totalGoal = 7;
        $progress = min(100, round(($docs / $totalGoal) * 100));

        return [
            'verified_count' => $docs,
            'total_goal' => $totalGoal,
            'progress' => $progress
        ];
    }

    public function index()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $keyword = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->participantModel->getParticipantBuilder();

        if ($keyword) {
            $builder->groupStart()
                ->like('participants.name', $keyword)
                ->orLike('participants.phone', $keyword)
                ->orLike('travel_packages.name', $keyword)
                ->orLike('users.full_name', $keyword)
                ->groupEnd();
        }

        if ($status === 'verified') {
            $builder->where('participants.status', 'verified');
        }
        elseif ($status === 'pending') {
            $builder->where('participants.status !=', 'verified');
        }

        $participants = $builder->paginate(20);
        $paymentModel = new PaymentModel();

        foreach ($participants as &$p) {
            // Payment Progress
            $paid = $paymentModel->getTotalPaid($p['id']);
            $p['total_paid'] = $paid['amount'] ?? 0;
            $price = $p['package_price'] ?? 0;

            if ($price > 0) {
                $p['payment_progress'] = min(100, round(($p['total_paid'] / $price) * 100));
            }
            else {
                $p['payment_progress'] = 0;
            }

            // Progress Kelengkapan Berkas (Simplified)
            $stats = $this->getDocStats($p['id']);
            $p['doc_count'] = $stats['verified_count'];
            $p['doc_progress'] = $stats['progress'];
        }

        $data = [
            'participants' => $participants,
            'pager' => $this->participantModel->pager,
            'keyword' => $keyword,
            'status' => $status
        ];
        return view('owner/participant/index', $data);
    }

    public function documents($id = null)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $docModel = new DocumentModel();

        if ($id) {
            $participant = $this->participantModel->find($id);
            if (!$participant) {
                return redirect()->to('owner/participant/documents')->with('error', 'Jamaah tidak ditemukan.');
            }

            $stats = $this->getDocStats($id);

            $data = [
                'participant' => $participant,
                'verified_count' => $stats['verified_count'],
                'total_goal' => $stats['total_goal'],
                'doc_progress' => $stats['progress'],
                'documents' => $docModel->where('participant_id', $id)->findAll()
            ];
            return view('owner/participant/documents_view', $data);
        }

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->participantModel->getParticipantBuilder();

        if ($search) {
            $builder->groupStart()
                ->like('participants.name', $search)
                ->orLike('participants.phone', $search)
                ->orLike('travel_packages.name', $search)
                ->orLike('users.full_name', $search)
                ->groupEnd();
        }

        $allParticipants = $builder->findAll();
        $filteredParticipants = [];

        foreach ($allParticipants as $p) {
            $stats = $this->getDocStats($p['id']);
            $p['doc_count'] = $stats['verified_count'];
            $p['doc_progress'] = $stats['progress'];

            // Completion Status: 100% = Selesai, < 100% = Proses
            $p['completion_status'] = ($p['doc_progress'] >= 100) ? 'selesai' : 'proses';

            if ($status === 'selesai') {
                if ($p['completion_status'] === 'selesai') {
                    $filteredParticipants[] = $p;
                }
            }
            elseif ($status === 'proses') {
                if ($p['completion_status'] === 'proses') {
                    $filteredParticipants[] = $p;
                }
            }
            else {
                $filteredParticipants[] = $p;
            }
        }

        $data = [
            'participants' => $filteredParticipants,
            'search' => $search,
            'status' => $status
        ];
        return view('owner/participant/documents_list', $data);
    }

    public function verifyDocument()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $docId = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $docModel = new DocumentModel();
        $docModel->update($docId, [
            'is_verified' => $status
        ]);

        $msg = $status ? 'Berkas berhasil diverifikasi.' : 'Verifikasi berkas dibatalkan.';
        return redirect()->back()->with('msg', $msg);
    }

    public function uploadDocument()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $id = $this->request->getPost('participant_id');
        $files = $this->request->getFileMultiple('files');
        $types = $this->request->getPost('types');
        $titles = $this->request->getPost('titles');

        if (!$files) {
            return redirect()->back()->with('error', 'Tidak ada file dipilih.');
        }

        $docModel = new DocumentModel();
        $uploadedCount = 0;

        foreach ($files as $index => $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $type = $types[$index] ?? 'other';
                $title = $titles[$index] ?? '';
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/uploads/documents', $newName);

                if ($type != 'other') {
                    $docModel->where('participant_id', $id)->where('type', $type)->delete();
                }

                $docModel->save([
                    'participant_id' => $id,
                    'type' => $type,
                    'title' => $title,
                    'file_path' => 'uploads/documents/' . $newName,
                    'is_verified' => 1
                ]);
                $uploadedCount++;
            }
        }

        if ($uploadedCount > 0) {
            return redirect()->back()->with('msg', "$uploadedCount berkas berhasil diupload.");
        }

        return redirect()->back()->with('error', 'Gagal mengupload berkas.');
    }

    public function receipt($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $participant = $this->participantModel->getParticipantBuilder()->where('participants.id', $id)->get()->getRowArray();
        if (!$participant) {
            return redirect()->back()->with('error', 'Jamaah tidak ditemukan.');
        }

        $paymentModel = new PaymentModel();
        $payments = $paymentModel->where('participant_id', $id)
            ->where('status', 'verified')
            ->orderBy('payment_date', 'ASC')
            ->findAll();

        $data = [
            'participant' => $participant,
            'payments' => $payments,
            'title' => 'Kwitansi Pendaftaran - ' . $participant['name']
        ];

        return view('owner/participant/receipt_print', $data);
    }

    public function paymentHistory($id)
    {
        if (session()->get('role') != 'owner') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $paymentModel = new PaymentModel();
        $history = $paymentModel->where('participant_id', $id)
            ->orderBy('payment_date', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $history
        ]);
    }

    public function transactionReceipt($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $paymentModel = new PaymentModel();
        $payment = $paymentModel->find($id);

        if (!$payment || $payment['status'] !== 'verified') {
            return redirect()->back()->with('error', 'Pembayaran tidak ditemukan atau belum diverifikasi.');
        }

        $participant = $this->participantModel->getParticipantBuilder()
            ->where('participants.id', $payment['participant_id'])
            ->get()->getRowArray();

        if (!$participant) {
            return redirect()->back()->with('error', 'Jamaah tidak ditemukan.');
        }

        // Get total paid up to now for balance info
        $paid = $paymentModel->getTotalPaid($payment['participant_id']);

        $data = [
            'participant' => $participant,
            'payment' => $payment,
            'total_paid' => $paid['amount'],
            'title' => 'Kwitansi Pembayaran - ' . $participant['name']
        ];

        return view('owner/participant/receipt_print', $data);
    }
}
