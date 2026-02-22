<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Cek List Atribut</h2>
        <p class="text-secondary mb-0">Kelola pengambilan perlengkapan jamaah</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <a href="<?= base_url('owner/equipment/participants') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('msg')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('msg') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Participant Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="d-flex align-items-start">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-person-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <div class="small text-secondary text-uppercase fw-bold mb-1">Identitas Jamaah</div>
                                <h5 class="fw-bold text-dark mb-1"><?= esc($participant['name']) ?></h5>
                                <div class="small text-secondary mb-1">
                                    <i class="bi bi-credit-card me-1"></i> <?= esc($participant['nik']) ?>
                                </div>
                                <div class="small text-secondary">
                                    <i class="bi bi-telephone me-1"></i> <?= esc($participant['phone']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="d-flex align-items-start">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="bi bi-box-seam-fill text-success fs-4"></i>
                            </div>
                            <div>
                                <div class="small text-secondary text-uppercase fw-bold mb-1">Paket Tour</div>
                                <h5 class="fw-bold text-dark mb-1"><?= esc($participant['package_name']) ?></h5>
                                <div class="small text-secondary">
                                    <i class="bi bi-tag me-1"></i> Rp <?= number_format($participant['package_price'], 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                <i class="bi bi-building text-info fs-4"></i>
                            </div>
                            <div>
                                <div class="small text-secondary text-uppercase fw-bold mb-1">Agensi</div>
                                <h5 class="fw-bold text-dark mb-1"><?= esc($participant['agency_name']) ?></h5>
                                <div class="small text-secondary">
                                    Pendaftar resmi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="card-header bg-light py-3 px-4 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">Daftar Item Perlengkapan</h6>
                <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold"><?= count($equipment) ?> Items</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-light-soft">
                                <th class="ps-4 py-3 border-0 text-secondary small fw-bold text-uppercase">Nama Atribut</th>
                                <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Status</th>
                                <th class="pe-4 py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Update Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($equipment)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-box-seam text-secondary opacity-25" style="font-size: 3rem;"></i>
                                            <p class="text-secondary mb-0 mt-3">Tidak ada atribut aktif ditemukan.</p>
                                            <small class="text-muted">Pastikan Atribut Master sudah diinput dan dalam status Aktif.</small>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($equipment as $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-<?= $item['status'] == 'collected' ? 'success' : 'warning' ?> opacity-10 p-2 me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-box-seam-fill text-<?= $item['status'] == 'collected' ? 'success' : 'warning' ?> opacity-100"></i>
                                            </div>
                                            <span class="fw-bold text-dark d-block"><?= esc($item['item_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <form action="<?= base_url('owner/equipment/update-status') ?>" method="post" id="form-status-<?= $item['id'] ?>">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                            <select name="status" class="form-select form-select-sm rounded-pill border-0 shadow-sm px-3 fw-bold <?= $item['status'] == 'collected' ? 'bg-success-soft text-success' : 'bg-warning-soft text-warning' ?>" onchange="document.getElementById('form-status-<?= $item['id'] ?>').submit()" style="min-width: 140px;">
                                                <option value="pending" <?= $item['status'] == 'pending' ? 'selected' : '' ?>>Belum Diambil</option>
                                                <option value="collected" <?= $item['status'] == 'collected' ? 'selected' : '' ?>>Sudah Diambil</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <?php if($item['collected_at']): ?>
                                            <div class="small">
                                                <span class="text-dark d-block fw-bold"><?= date('d/m/Y H:i', strtotime($item['collected_at'])) ?></span>
                                                <span class="text-muted smaller">Oleh: <?= esc($item['collected_by']) ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-secondary small italic">N/A</span>
                                        <?php endif; ?>
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
