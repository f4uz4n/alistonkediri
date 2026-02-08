<?php

namespace App\Controllers;

use App\Models\HotelModel;
use App\Models\RoomModel;
use App\Models\CityModel;

class Hotel extends BaseController
{
    protected $hotelModel;
    protected $roomModel;
    protected $cityModel;

    public function __construct()
    {
        $this->hotelModel = new HotelModel();
        $this->roomModel = new RoomModel();
        $this->cityModel = new CityModel();
    }

    public function index()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $data = [
            'hotels' => $this->hotelModel->findAll(),
            'title' => 'Manajemen Hotel'
        ];
        return view('owner/hotel/index', $data);
    }

    public function create()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $data = [
            'cities' => $this->cityModel->getOrdered(),
        ];
        return view('owner/hotel/create', $data);
    }

    public function store()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'city' => $this->request->getPost('city'),
            'star_rating' => $this->request->getPost('star_rating'),
            'address' => $this->request->getPost('address'),
            'facilities' => $this->request->getPost('facilities'),
        ];

        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $dir = FCPATH . 'uploads/hotels';
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $newName = $img->getRandomName();
            $img->move($dir, $newName);
            $data['image'] = 'uploads/hotels/' . $newName;
        }

        if ($this->hotelModel->insert($data)) {
            return redirect()->to('owner/hotels')->with('msg', 'Hotel berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan hotel.');
    }

    public function edit($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $hotel = $this->hotelModel->find($id);
        if (!$hotel)
            return redirect()->to('owner/hotels')->with('error', 'Hotel tidak ditemukan.');

        $data = [
            'hotel' => $hotel,
            'cities' => $this->cityModel->getOrdered(),
        ];
        return view('owner/hotel/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'city' => $this->request->getPost('city'),
            'star_rating' => $this->request->getPost('star_rating'),
            'address' => $this->request->getPost('address'),
            'facilities' => $this->request->getPost('facilities'),
        ];

        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $dir = FCPATH . 'uploads/hotels';
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $newName = $img->getRandomName();
            $img->move($dir, $newName);
            $data['image'] = 'uploads/hotels/' . $newName;
            $old = $this->hotelModel->find($id);
            if (!empty($old['image']) && is_file(FCPATH . $old['image'])) {
                @unlink(FCPATH . $old['image']);
            }
        }

        if ($this->hotelModel->update($id, $data)) {
            return redirect()->to('owner/hotels')->with('msg', 'Hotel berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui hotel.');
    }

    public function rooms($hotelId)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $hotel = $this->hotelModel->find($hotelId);
        if (!$hotel)
            return redirect()->to('owner/hotels')->with('error', 'Hotel tidak ditemukan.');

        $data = [
            'hotel' => $hotel,
            'rooms' => $this->roomModel->getRoomsByHotel($hotelId),
            'title' => 'Kamar Hotel: ' . $hotel['name']
        ];
        return view('owner/hotel/rooms', $data);
    }

    public function storeRoom()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $hotelId = $this->request->getPost('hotel_id');

        $data = [
            'hotel_id' => $hotelId,
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'price_per_pax' => $this->request->getPost('price_per_pax'),
            'facilities' => $this->request->getPost('facilities'),
        ];

        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $dir = FCPATH . 'uploads/rooms';
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $newName = $img->getRandomName();
            $img->move($dir, $newName);
            $data['image'] = 'uploads/rooms/' . $newName;
        }

        if ($this->roomModel->insert($data)) {
            return redirect()->back()->with('msg', 'Kamar berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kamar.');
    }

    /**
     * Simpan banyak kamar sekaligus (multiple input).
     */
    public function storeRooms()
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $hotelId = (int) $this->request->getPost('hotel_id');
        if (!$hotelId) {
            return redirect()->back()->with('error', 'Hotel tidak valid.');
        }

        $names = $this->request->getPost('room_name');
        $types = $this->request->getPost('room_type');
        $prices = $this->request->getPost('room_price');
        $facilitiesArr = $this->request->getPost('room_facilities');

        if (!is_array($names)) {
            $names = $names ? [$names] : [];
        }
        if (!is_array($types)) {
            $types = $types ? [$types] : [];
        }
        if (!is_array($prices)) {
            $prices = $prices ? [$prices] : [];
        }
        if (!is_array($facilitiesArr)) {
            $facilitiesArr = $facilitiesArr !== null ? [$facilitiesArr] : [];
        }

        $dir = FCPATH . 'uploads/rooms';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $inserted = 0;
        foreach ($names as $i => $name) {
            $name = trim($name ?? '');
            if ($name === '') {
                continue;
            }

            $data = [
                'hotel_id' => $hotelId,
                'name' => $name,
                'type' => $types[$i] ?? 'Quad',
                'price_per_pax' => (float) ($prices[$i] ?? 0),
                'facilities' => $facilitiesArr[$i] ?? '',
            ];

            $file = $this->request->getFile('room_image_' . $i);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move($dir, $newName);
                $data['image'] = 'uploads/rooms/' . $newName;
            }

            if ($this->roomModel->insert($data)) {
                $inserted++;
            }
        }

        if ($inserted > 0) {
            return redirect()->back()->with('msg', $inserted . ' kamar berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Tidak ada data kamar yang valid. Isi minimal nama kamar.');
    }

    public function editRoom($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $room = $this->roomModel->find($id);
        if (!$room) {
            return redirect()->to('owner/hotels')->with('error', 'Kamar tidak ditemukan.');
        }

        $hotel = $this->hotelModel->find($room['hotel_id']);
        if (!$hotel) {
            return redirect()->to('owner/hotels')->with('error', 'Hotel tidak ditemukan.');
        }

        $data = [
            'room' => $room,
            'hotel' => $hotel,
            'title' => 'Edit Kamar: ' . $room['name'],
        ];
        return view('owner/hotel/room_edit', $data);
    }

    public function updateRoom($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $room = $this->roomModel->find($id);
        if (!$room) {
            return redirect()->to('owner/hotels')->with('error', 'Kamar tidak ditemukan.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'price_per_pax' => $this->request->getPost('price_per_pax'),
            'facilities' => $this->request->getPost('facilities'),
        ];

        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $dir = FCPATH . 'uploads/rooms';
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $newName = $img->getRandomName();
            $img->move($dir, $newName);
            $data['image'] = 'uploads/rooms/' . $newName;
            if (!empty($room['image']) && is_file(FCPATH . $room['image'])) {
                @unlink(FCPATH . $room['image']);
            }
        }

        if ($this->roomModel->update($id, $data)) {
            return redirect()->to('owner/hotels/' . $room['hotel_id'] . '/rooms')->with('msg', 'Kamar berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kamar.');
    }

    public function deleteRoom($id)
    {
        if (session()->get('role') != 'owner') {
            return redirect()->to('/login');
        }

        $this->roomModel->delete($id);
        return redirect()->back()->with('msg', 'Kamar berhasil dihapus.');
    }
}
