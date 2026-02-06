<?php

namespace App\Controllers;

use App\Models\PackageModel;

class Package extends BaseController
{
    protected $packageModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
    }

    public function index()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $data = [
            'packages' => $this->packageModel->findAll()
        ];
        return view('owner/package/index', $data);
    }

    public function create()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        return view('owner/package/create');
    }

    public function store()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $inclusions = $this->request->getPost('inclusions');
        $freebies = $this->request->getPost('freebies');

        $parseList = function ($text) {
            if (!$text)
                return [];
            $lines = preg_split("/\r\n|\n|\r/", $text);
            return array_values(array_filter(array_map('trim', $lines)));
        };

        $data = [
            'name' => $this->request->getPost('name'),
            'departure_date' => $this->request->getPost('departure_date'),
            'duration' => $this->request->getPost('duration'),
            'location_start_end' => $this->request->getPost('location_start_end'),
            'hotel_mekkah' => $this->request->getPost('hotel_mekkah'),
            'hotel_mekkah_stars' => $this->request->getPost('hotel_mekkah_stars'),
            'hotel_madinah' => $this->request->getPost('hotel_madinah'),
            'hotel_madinah_stars' => $this->request->getPost('hotel_madinah_stars'),
            'airline' => $this->request->getPost('airline'),
            'flight_route' => $this->request->getPost('flight_route'),
            'price' => $this->request->getPost('price'),
            'price_unit' => $this->request->getPost('price_unit'),
            'inclusions' => json_encode($parseList($inclusions)),
            'freebies' => json_encode($parseList($freebies)),
            'commission_per_pax' => $this->request->getPost('commission_per_pax'),
            'branch_info' => $this->request->getPost('branch_info')
        ];

        // Handle Image Upload
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move('uploads/packages', $newName);
            $data['image'] = 'uploads/packages/' . $newName;
        }

        if ($this->packageModel->insert($data)) {
            return redirect()->to('/package')->with('msg', 'Paket berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan paket');
    }

    public function edit($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to('/package')->with('error', 'Paket tidak ditemukan');
        }

        // Convert JSON back to newline-separated string for textarea
        $package['inclusions_text'] = implode("\n", json_decode($package['inclusions'], true) ?? []);
        $package['freebies_text'] = implode("\n", json_decode($package['freebies'], true) ?? []);

        return view('owner/package/edit', ['package' => $package]);
    }

    public function update($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to('/package')->with('error', 'Paket tidak ditemukan');
        }

        $inclusions = $this->request->getPost('inclusions');
        $freebies = $this->request->getPost('freebies');

        $parseList = function ($text) {
            if (!$text)
                return [];
            $lines = preg_split("/\r\n|\n|\r/", $text);
            return array_values(array_filter(array_map('trim', $lines)));
        };

        $data = [
            'name' => $this->request->getPost('name'),
            'departure_date' => $this->request->getPost('departure_date'),
            'duration' => $this->request->getPost('duration'),
            'location_start_end' => $this->request->getPost('location_start_end'),
            'hotel_mekkah' => $this->request->getPost('hotel_mekkah'),
            'hotel_mekkah_stars' => $this->request->getPost('hotel_mekkah_stars'),
            'hotel_madinah' => $this->request->getPost('hotel_madinah'),
            'hotel_madinah_stars' => $this->request->getPost('hotel_madinah_stars'),
            'airline' => $this->request->getPost('airline'),
            'flight_route' => $this->request->getPost('flight_route'),
            'price' => $this->request->getPost('price'),
            'price_unit' => $this->request->getPost('price_unit'),
            'inclusions' => json_encode($parseList($inclusions)),
            'freebies' => json_encode($parseList($freebies)),
            'commission_per_pax' => $this->request->getPost('commission_per_pax'),
            'branch_info' => $this->request->getPost('branch_info')
        ];

        // Handle Image Upload
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            // Delete old image if exists
            if ($package['image'] && file_exists($package['image'])) {
                unlink($package['image']);
            }

            $newName = $img->getRandomName();
            $img->move('uploads/packages', $newName);
            $data['image'] = 'uploads/packages/' . $newName;
        }

        if ($this->packageModel->update($id, $data)) {
            return redirect()->to('/package')->with('msg', 'Paket berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui paket');
    }

    public function delete($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $package = $this->packageModel->find($id);
        if ($package && $package['image'] && file_exists($package['image'])) {
            unlink($package['image']);
        }

        $this->packageModel->delete($id);
        return redirect()->to('/package')->with('msg', 'Paket berhasil dihapus');
    }
}
