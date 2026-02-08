<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <a href="<?= base_url('owner/hotels') ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-3"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        <h2 class="fw-800 text-dark mb-1">Edit Hotel</h2>
        <p class="text-secondary mb-4"><?= esc($hotel['name']) ?></p>

        <form action="<?= base_url('owner/hotels/update/' . $hotel['id']) ?>" method="post" enctype="multipart/form-data" class="card border-0 shadow-sm rounded-4 p-4">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Hotel</label>
                <input type="text" name="name" class="form-control form-control-lg bg-light border-0" required value="<?= esc($hotel['name']) ?>">
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kota</label>
                    <select name="city" class="form-select form-select-lg bg-light border-0" required>
                        <option value="">Pilih Kota</option>
                        <?php foreach ($cities ?? [] as $city): ?>
                        <option value="<?= esc($city['name']) ?>" <?= ($hotel['city'] ?? '') === $city['name'] ? 'selected' : '' ?>><?= esc($city['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Bintang</label>
                    <select name="star_rating" class="form-select form-select-lg bg-light border-0" required>
                        <?php for ($i = 3; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= ((int)($hotel['star_rating'] ?? 0)) === $i ? 'selected' : '' ?>><?= $i ?> Bintang</option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Alamat</label>
                <textarea name="address" class="form-control bg-light border-0" rows="2"><?= esc($hotel['address'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Fasilitas Hotel</label>
                <textarea name="facilities" class="form-control bg-light border-0" rows="3" placeholder="Contoh: WiFi, AC, Restoran, Kolam renang"><?= esc($hotel['facilities'] ?? '') ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Gambar Hotel (opsional)</label>
                <?php if (!empty($hotel['image']) && is_file(FCPATH . $hotel['image'])): ?>
                    <div class="mb-2"><img src="<?= base_url($hotel['image']) ?>" alt="Hotel" class="img-thumbnail" style="max-height:120px"></div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold"><i class="bi bi-check2 me-2"></i>Simpan Perubahan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
