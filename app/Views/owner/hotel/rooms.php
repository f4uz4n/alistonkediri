<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6">
        <a href="<?= base_url('owner/hotels') ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-2"><i class="bi bi-arrow-left me-2"></i>Daftar Hotel</a>
        <h2 class="fw-800 text-dark mb-1">Kamar — <?= esc($hotel['name']) ?></h2>
        <p class="text-secondary mb-0"><?= esc($hotel['city']) ?> · <?= (int)$hotel['star_rating'] ?> Bintang</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-5 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Kamar (Multiple)</h6>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" id="btnAddRoomRow"><i class="bi bi-plus-lg me-1"></i> Baris Kamar</button>
            </div>
            <div class="card-body">
                <form action="<?= base_url('owner/hotels/rooms/store-multiple') ?>" method="post" enctype="multipart/form-data" id="formMultipleRooms">
                    <?= csrf_field() ?>
                    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
                    <div id="roomRows">
                        <!-- Baris kamar 0 -->
                        <div class="room-row border rounded-3 p-3 mb-3 bg-light" data-index="0">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold text-secondary">Kamar #1</span>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-room-row" title="Hapus baris"><i class="bi bi-trash"></i></button>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small mb-0">Nama / Tipe Kamar</label>
                                <input type="text" name="room_name[]" class="form-control form-control-sm bg-white border-0" placeholder="Contoh: Standard Quad">
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small mb-0">Tipe</label>
                                    <select name="room_type[]" class="form-select form-select-sm bg-white border-0">
                                        <option value="Quad">Quad</option>
                                        <option value="Triple">Triple</option>
                                        <option value="Double">Double</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-0">Harga/Pax (Rp)</label>
                                    <input type="text" name="room_price[]" class="form-control form-control-sm bg-white border-0 format-rupiah" placeholder="0" data-value="0">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small mb-0">Fasilitas</label>
                                <textarea name="room_facilities[]" class="form-control form-control-sm bg-white border-0" rows="1" placeholder="AC, TV, WiFi"></textarea>
                            </div>
                            <div>
                                <label class="form-label small mb-0">Gambar Kamar</label>
                                <input type="file" name="room_image_0" class="form-control form-control-sm bg-white border-0" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-check2 me-1"></i> Simpan Semua Kamar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-light border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-door-open me-2"></i>Daftar Kamar</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-2 small fw-bold text-secondary">Gambar</th>
                            <th class="py-2 small fw-bold text-secondary">Nama</th>
                            <th class="py-2 small fw-bold text-secondary">Tipe</th>
                            <th class="py-2 small fw-bold text-secondary">Fasilitas</th>
                            <th class="py-2 small fw-bold text-secondary text-end">Harga/Pax</th>
                            <th class="pe-4 py-2 small fw-bold text-secondary text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rooms)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">Belum ada kamar. Tambah kamar di form kiri.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rooms as $r): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if (!empty($r['image']) && is_file(FCPATH . $r['image'])): ?>
                                        <img src="<?= base_url($r['image']) ?>" alt="" class="rounded" style="width:50px;height:50px;object-fit:cover">
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?= esc($r['name']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= esc($r['type']) ?></span></td>
                                <td class="small text-secondary"><?= esc($r['facilities'] ? mb_strimwidth($r['facilities'], 0, 40, '…') : '—') ?></td>
                                <td class="text-end">Rp <?= number_format($r['price_per_pax'], 0, ',', '.') ?></td>
                                <td class="pe-4 text-end">
                                    <a href="<?= base_url('owner/hotels/rooms/edit/' . $r['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill me-1">Edit</a>
                                    <a href="<?= base_url('owner/hotels/rooms/delete/' . $r['id']) ?>" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Hapus kamar ini?');">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var roomRows = document.getElementById('roomRows');
    var btnAdd = document.getElementById('btnAddRoomRow');
    var form = document.getElementById('formMultipleRooms');

    function getNextIndex() {
        var rows = roomRows.querySelectorAll('.room-row');
        var max = -1;
        rows.forEach(function(r) {
            var i = parseInt(r.getAttribute('data-index'), 10);
            if (i > max) max = i;
        });
        return max + 1;
    }

    function updateRowLabels() {
        roomRows.querySelectorAll('.room-row').forEach(function(row, idx) {
            var label = row.querySelector('.text-secondary.fw-bold');
            if (label) label.textContent = 'Kamar #' + (idx + 1);
            row.setAttribute('data-index', idx);
            var fileInput = row.querySelector('input[type="file"]');
            if (fileInput) fileInput.name = 'room_image_' + idx;
        });
    }

    function addRow() {
        var idx = getNextIndex();
        var div = document.createElement('div');
        div.className = 'room-row border rounded-3 p-3 mb-3 bg-light';
        div.setAttribute('data-index', idx);
        div.innerHTML =
            '<div class="d-flex justify-content-between align-items-center mb-2">' +
                '<span class="small fw-bold text-secondary">Kamar #' + (idx + 1) + '</span>' +
                '<button type="button" class="btn btn-sm btn-outline-danger remove-room-row" title="Hapus baris"><i class="bi bi-trash"></i></button>' +
            '</div>' +
            '<div class="mb-2">' +
                '<label class="form-label small mb-0">Nama / Tipe Kamar</label>' +
                '<input type="text" name="room_name[]" class="form-control form-control-sm bg-white border-0" placeholder="Contoh: Standard Quad">' +
            '</div>' +
            '<div class="row g-2 mb-2">' +
                '<div class="col-6">' +
                    '<label class="form-label small mb-0">Tipe</label>' +
                    '<select name="room_type[]" class="form-select form-select-sm bg-white border-0">' +
                        '<option value="Quad">Quad</option><option value="Triple">Triple</option><option value="Double">Double</option>' +
                    '</select>' +
                '</div>' +
'<div class="col-6">' +
                '<label class="form-label small mb-0">Harga/Pax (Rp)</label>' +
                '<input type="text" name="room_price[]" class="form-control form-control-sm bg-white border-0 format-rupiah" placeholder="0" data-value="0">' +
            '</div>' +
            '</div>' +
            '<div class="mb-2">' +
                '<label class="form-label small mb-0">Fasilitas</label>' +
                '<textarea name="room_facilities[]" class="form-control form-control-sm bg-white border-0" rows="1" placeholder="AC, TV, WiFi"></textarea>' +
            '</div>' +
            '<div>' +
                '<label class="form-label small mb-0">Gambar Kamar</label>' +
                '<input type="file" name="room_image_' + idx + '" class="form-control form-control-sm bg-white border-0" accept="image/*">' +
            '</div>';
        roomRows.appendChild(div);
        div.querySelector('.remove-room-row').addEventListener('click', function() { removeRow(div); });
    }

    function removeRow(rowEl) {
        var rows = roomRows.querySelectorAll('.room-row');
        if (rows.length <= 1) return;
        rowEl.remove();
        updateRowLabels();
    }

    btnAdd.addEventListener('click', function() {
        addRow();
    });

    roomRows.addEventListener('click', function(e) {
        if (e.target.closest('.remove-room-row')) {
            removeRow(e.target.closest('.room-row'));
        }
    });

    form.addEventListener('submit', function() {
        updateRowLabels();
    });
})();
</script>
<?= $this->endSection() ?>
