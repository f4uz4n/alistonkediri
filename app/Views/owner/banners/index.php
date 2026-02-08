<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row align-items-center mb-5">
    <div class="col-12">
        <h2 class="fw-800 text-dark mb-1">Banner Beranda</h2>
        <p class="text-secondary mb-0">Upload foto banner untuk ditampilkan sebagai slider di kolom banner halaman depan (beranda)</p>
    </div>
</div>

<?php if (session()->getFlashdata('msg')): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 py-4 px-4 border-bottom">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-cloud-upload me-2 text-primary"></i>Upload Banner Beranda</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('owner/banners/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Foto</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted">JPG/PNG, maks. 5MB. Rasio lebar tinggi disarankan untuk banner (mis. 1200×400).</small>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold w-100">Unggah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 py-4 px-4 border-bottom">
                <h5 class="fw-bold text-dark mb-0">Daftar Banner (urutan slider)</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($banners)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-images fs-1 opacity-25"></i>
                        <p class="mb-0 mt-2">Belum ada banner. Upload foto untuk menampilkan slider di halaman beranda.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">#</th>
                                    <th class="py-3">Preview</th>
                                    <th class="py-3">File</th>
                                    <th class="pe-4 py-3 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $i => $b): ?>
                                <tr>
                                    <td class="ps-4"><?= $i + 1 ?></td>
                                    <td>
                                        <a href="<?= base_url($b['image']) ?>" target="_blank" class="d-inline-block">
                                            <img src="<?= base_url($b['image']) ?>" alt="Banner" class="rounded" style="max-height: 60px; max-width: 120px; object-fit: cover;">
                                        </a>
                                    </td>
                                    <td class="small text-secondary"><?= esc(basename($b['image'])) ?></td>
                                    <td class="pe-4 text-end">
                                        <a href="<?= base_url('owner/banners/delete/'.$b['id']) ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Hapus banner ini?');">Hapus</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
