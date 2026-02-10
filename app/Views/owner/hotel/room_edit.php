<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <a href="<?= base_url('owner/hotels/' . $hotel['id'] . '/rooms') ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-3"><i class="bi bi-arrow-left me-2"></i>Kembali ke Kamar</a>
        <h2 class="fw-800 text-dark mb-1">Edit Kamar</h2>
        <p class="text-secondary mb-4"><?= esc($hotel['name']) ?> — <?= esc($room['name']) ?></p>

        <form action="<?= base_url('owner/hotels/rooms/update/' . $room['id']) ?>" method="post" enctype="multipart/form-data" class="card border-0 shadow-sm rounded-4 p-4">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama / Tipe Kamar</label>
                <input type="text" name="name" class="form-control bg-light border-0" required value="<?= esc($room['name']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Tipe</label>
                <select name="type" class="form-select bg-light border-0" required>
                    <option value="Quad" <?= ($room['type'] ?? '') === 'Quad' ? 'selected' : '' ?>>Quad</option>
                    <option value="Triple" <?= ($room['type'] ?? '') === 'Triple' ? 'selected' : '' ?>>Triple</option>
                    <option value="Double" <?= ($room['type'] ?? '') === 'Double' ? 'selected' : '' ?>>Double</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Harga per Pax (Rp)</label>
                <?php $pricePax = (float)($room['price_per_pax'] ?? 0); ?>
                <input type="text" name="price_per_pax" class="form-control bg-light border-0 format-rupiah" required placeholder="0" data-value="<?= (int) $pricePax ?>" value="<?= $pricePax ? number_format($pricePax, 0, '', '.') : '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Fasilitas Kamar</label>
                <textarea name="facilities" class="form-control bg-light border-0" rows="3"><?= esc($room['facilities'] ?? '') ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Gambar Kamar (opsional)</label>
                <?php if (!empty($room['image']) && is_file(FCPATH . $room['image'])): ?>
                    <div class="mb-2"><img src="<?= base_url($room['image']) ?>" alt="Kamar" class="img-thumbnail" style="max-height:120px"></div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold"><i class="bi bi-check2 me-2"></i>Simpan Perubahan</button>
        </form>

        <!-- Master Bed / Kasur -->
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-header bg-light border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-moon-stars me-2"></i>Master Bed / Kasur</h6>
                <p class="small text-secondary mb-0 mt-1">Tambah tipe bed dengan harga; dipakai saat upgrade fasilitas jamaah.</p>
            </div>
            <div class="card-body">
                <form action="<?= base_url('owner/hotels/rooms/beds/store') ?>" method="post" class="mb-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="room_id" value="<?= (int)$room['id'] ?>">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Nama Bed</label>
                            <input type="text" name="name" class="form-control form-control-sm bg-light border-0" placeholder="Contoh: Extra Bed" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Harga (Rp)</label>
                            <input type="text" name="price" class="form-control form-control-sm bg-light border-0 format-rupiah" placeholder="0" data-value="0" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100"><i class="bi bi-plus-lg me-1"></i>Tambah Bed</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-2 small fw-bold text-secondary">Nama</th>
                                <th class="py-2 small fw-bold text-secondary text-end">Harga</th>
                                <th class="py-2 small fw-bold text-secondary text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($beds)): ?>
                            <tr><td colspan="3" class="text-center py-3 text-muted small">Belum ada master bed. Tambah di form atas.</td></tr>
                            <?php else: ?>
                            <?php foreach ($beds as $b): ?>
                            <tr>
                                <td class="fw-bold"><?= esc($b['name']) ?></td>
                                <td class="text-end">Rp <?= number_format((float)$b['price'], 0, ',', '.') ?></td>
                                <td class="text-end">
                                    <form action="<?= base_url('owner/hotels/rooms/beds/delete/' . $b['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus bed ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">Hapus</button>
                                    </form>
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
</div>
<?= $this->endSection() ?>
