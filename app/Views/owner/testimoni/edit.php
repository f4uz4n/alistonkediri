<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $t = $testimonial ?? []; $packages = $packages ?? []; ?>

<div class="row align-items-center mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('owner/testimoni') ?>" class="text-decoration-none text-secondary">Testimoni Jamaah</a></li>
                <li class="breadcrumb-item active fw-bold" aria-current="page">Edit Testimoni</li>
            </ol>
        </nav>
        <h2 class="fw-800 text-dark mb-1">Edit Testimoni</h2>
        <p class="text-secondary mb-0">Ubah nama, paket, isi testimoni, rating, atau status</p>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="<?= base_url('owner/testimoni/update/' . ($t['id'] ?? '')) ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= esc(old('name', $t['name'] ?? '')) ?>" required minlength="2" maxlength="255" placeholder="Nama pemberi testimoni">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold">Paket</label>
                    <select name="package_id" class="form-select">
                        <option value="">— Tidak memilih paket —</option>
                        <?php foreach ($packages as $p): ?>
                        <option value="<?= (int) $p['id'] ?>" <?= (int)($t['package_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Isi Testimoni <span class="text-danger">*</span></label>
                    <textarea name="testimonial" class="form-control" rows="5" required minlength="10" placeholder="Tulis testimoni (min. 10 karakter)"><?= esc(old('testimonial', $t['testimonial'] ?? '')) ?></textarea>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                    <select name="rating" class="form-select" required>
                        <?php for ($r = 1; $r <= 5; $r++): ?>
                        <option value="<?= $r ?>" <?= (int)($t['rating'] ?? 5) === $r ? 'selected' : '' ?>><?= $r ?> bintang</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="pending" <?= ($t['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending (belum dipublikasikan)</option>
                        <option value="verified" <?= ($t['status'] ?? '') === 'verified' ? 'selected' : '' ?>>Dipublikasikan</option>
                    </select>
                </div>
                <div class="col-12 pt-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                    <a href="<?= base_url('owner/testimoni') ?>" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
