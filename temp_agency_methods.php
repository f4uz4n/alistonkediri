<?php

namespace App\Controllers;

class Agency extends BaseController
{
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
            'title' => 'Cek List & Kelengkapan - ' . $participant['name'],
            'participant' => $participant,
            'documents' => $docsFormatted,
            'freebies' => $freebies,
            'collectedMap' => $collectedMap
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
        $existing = $equipmentModel->where('participant_id', $id)
            ->where('item_name', $itemName)
            ->first();

        if ($existing) {
            $equipmentModel->update($existing['id'], [
                'status' => $status,
                'collected_at' => ($status === 'collected') ? date('Y-m-d H:i:s') : null,
                'collected_by' => ($status === 'collected') ? session()->get('user_id') : null
            ]);
        }
        else {
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
