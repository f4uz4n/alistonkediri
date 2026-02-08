<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Master Kota</h2>
        <p class="text-secondary mb-0">Kelola daftar kota untuk hotel dan paket (contoh: Mekkah, Madinah)</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <a href="<?= base_url('owner/cities/create') ?>" class="btn btn-primary rounded-pill px-4 fw-bold">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kota
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase">No</th>
                            <th class="py-3 text-secondary small fw-bold text-uppercase">Nama Kota</th>
                            <th class="py-3 text-secondary small fw-bold text-uppercase">Urutan</th>
                            <th class="pe-4 py-3 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cities)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="bi bi-geo-alt text-muted fs-1 d-block mb-3"></i>
                                    <p class="text-secondary mb-0">Belum ada kota. Klik "Tambah Kota" untuk menambah.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cities as $i => $c): ?>
                            <tr>
                                <td class="ps-4"><?= $i + 1 ?></td>
                                <td class="fw-bold"><?= esc($c['name']) ?></td>
                                <td><?= (int)($c['sort_order'] ?? 0) ?></td>
                                <td class="pe-4 text-end">
                                    <a href="<?= base_url('owner/cities/edit/' . $c['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill me-1">Edit</a>
                                    <a href="<?= base_url('owner/cities/delete/' . $c['id']) ?>" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Hapus kota ini?');">Hapus</a>
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
<?= $this->endSection() ?>
