<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Pengambilan Atribut</h2>
        <p class="text-secondary">Pantau status distribusi perlengkapan jamaah</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <a href="<?= base_url('owner/equipment/sync-all') ?>" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('Sinkronisasi masal akan membuat checklist atribut untuk SEMUA jamaah yang belum memilikinya. Lanjutkan?')">
            <i class="bi bi-arrow-repeat me-2"></i>Sinkronisasi Masal
        </a>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
    <div class="card-body p-4">
        <form action="<?= base_url('owner/equipment/participants') ?>" method="get" class="row g-3">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold text-secondary">Cari Jamaah</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Nama atau NIK..." value="<?= esc($filters['search'] ?? '') ?>">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small fw-bold text-secondary">Dari Tanggal Daftar</label>
                <input type="date" name="start_date" class="form-control bg-light border-0" value="<?= esc($filters['start_date'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small fw-bold text-secondary">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control bg-light border-0" value="<?= esc($filters['end_date'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end">
                <div class="d-grid w-100 gap-2 d-flex">
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold flex-grow-1">
                        Filter
                    </button>
                    <a href="<?= base_url('owner/equipment/participants') ?>" class="btn btn-light rounded-pill border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (session()->getFlashdata('msg')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('msg') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-secondary small fw-bold text-uppercase">Jamaah</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase">Agensi</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Progres Atribut</th>
                            <th class="pe-4 py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($participants)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-people text-secondary opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-secondary mb-0 mt-3">Tidak ada data jamaah yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($participants as $part): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info-soft text-info rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block"><?= esc($part['name']) ?></span>
                                            <small class="text-muted">NIK: <?= esc($part['nik']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary small"><?= esc($part['agency_name']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($part['total_items'] > 0): ?>
                                        <div class="d-flex flex-column align-items-center" style="min-width: 120px;">
                                            <div class="progress w-100 rounded-pill mb-1" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $part['progress_percent'] ?>%"></div>
                                            </div>
                                            <small class="fw-bold <?= $part['progress_percent'] == 100 ? 'text-success' : 'text-primary' ?>">
                                                <?= $part['collected_items'] ?> / <?= $part['total_items'] ?> Item
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-soft text-secondary rounded-pill px-3 py-2 fw-bold italic small">
                                            Belum Sinkron
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="<?= base_url('owner/equipment/checklist/'.$part['id']) ?>" class="btn <?= $part['progress_percent'] == 100 ? 'btn-outline-success' : 'btn-primary' ?> btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                        <?= $part['progress_percent'] == 100 ? '<i class="bi bi-check2-all me-1"></i>Selesai' : 'Cek List Atribut <i class="bi bi-arrow-right ms-1"></i>' ?>
                                    </a>
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
