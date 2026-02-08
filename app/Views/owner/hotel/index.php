<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Master Hotel & Kamar</h2>
        <p class="text-secondary mb-0">Kelola data hotel dan kamar untuk pilihan upgrade jamaah</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <a href="<?= base_url('owner/hotels/create') ?>" class="btn btn-primary rounded-pill px-4 fw-bold">
            <i class="bi bi-plus-lg me-2"></i>Tambah Hotel
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
                            <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase">Gambar</th>
                            <th class="py-3 text-secondary small fw-bold text-uppercase">Nama Hotel</th>
                            <th class="py-3 text-secondary small fw-bold text-uppercase">Kota</th>
                            <th class="py-3 text-secondary small fw-bold text-uppercase text-center">Bintang</th>
                            <th class="py-3 text-secondary small fw-bold text-uppercase">Alamat / Fasilitas</th>
                            <th class="pe-4 py-3 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($hotels)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-building text-muted fs-1 d-block mb-3"></i>
                                    <p class="text-secondary mb-0">Belum ada hotel. Klik "Tambah Hotel" untuk menambah.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($hotels as $h): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if (!empty($h['image']) && is_file(FCPATH . $h['image'])): ?>
                                        <img src="<?= base_url($h['image']) ?>" alt="" class="rounded" style="width:50px;height:50px;object-fit:cover">
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?= esc($h['name']) ?></td>
                                <td><?= esc($h['city']) ?></td>
                                <td class="text-center"><span class="badge bg-warning text-dark"><?= (int)$h['star_rating'] ?> ★</span></td>
                                <td class="small text-secondary"><?= esc($h['address'] ?: '—') ?><?= !empty($h['facilities']) ? ' · ' . mb_strimwidth($h['facilities'], 0, 50, '…') : '' ?></td>
                                <td class="pe-4 text-end">
                                    <a href="<?= base_url('owner/hotels/' . $h['id'] . '/rooms') ?>" class="btn btn-outline-primary btn-sm rounded-pill me-1">
                                        <i class="bi bi-door-open me-1"></i> Kamar
                                    </a>
                                    <a href="<?= base_url('owner/hotels/edit/' . $h['id']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
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
