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
                <input type="number" name="price_per_pax" class="form-control bg-light border-0" required min="0" step="0.01" value="<?= esc($room['price_per_pax']) ?>">
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
    </div>
</div>
<?= $this->endSection() ?>
