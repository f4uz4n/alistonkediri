<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Komisi Agensi</h2>
        <p class="text-secondary">Kelola dan pantau pembagian komisi untuk mitra agensi</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if(session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-pill px-4 py-2 d-inline-block shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-pill px-4 py-2 d-inline-block shadow-sm ms-2">
                <i class="bi bi-exclamation-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <form action="<?= base_url('owner/commissions') ?>" method="get" class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold text-secondary">Cari Agensi / Paket</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search text-secondary"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-0" placeholder="Nama agensi atau paket..." value="<?= esc($search ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-secondary">Filter Paket</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-briefcase text-secondary"></i></span>
                            <select name="package_id" class="form-select bg-light border-0 text-dark">
                                <option value="">Semua Paket</option>
                                <?php foreach($packages as $pkg): ?>
                                    <option value="<?= $pkg['id'] ?>" <?= ($package_id ?? '') == $pkg['id'] ? 'selected' : '' ?>><?= esc($pkg['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label small fw-bold text-secondary">Rentang Tanggal (Pendaftaran/Dibuat)</label>
                        <div class="d-flex gap-2">
                            <input type="date" name="start_date" class="form-control bg-light border-0" value="<?= esc($start_date ?? '') ?>">
                            <span class="align-self-center text-secondary">-</span>
                            <input type="date" name="end_date" class="form-control bg-light border-0" value="<?= esc($end_date ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-12 text-end mt-3">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url('owner/commissions') ?>" class="btn btn-light rounded-pill px-4 fw-bold border">Reset</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-filter me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-secondary small fw-bold text-uppercase">Agensi & Paket</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Total Pax</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Komisi Sistem</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Komisi Final</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Status</th>
                            <th class="pe-4 py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($commissions)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-wallet2 text-muted fs-1 mb-3"></i>
                                        <p class="text-secondary mb-0">Tidak ada data komisi yang ditemukan atau sesuai kriteria.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($commissions as $comm): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark d-block"><?= esc($comm['agency_name']) ?></span>
                                        <small class="text-secondary"><i class="bi bi-airplane-fill me-1"></i> <?= esc($comm['package_name']) ?></small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-soft text-primary rounded-pill px-3">
                                        <?= $comm['rate'] > 0 ? round($comm['amount_calculated'] / $comm['rate']) : 0 ?> Pax
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="text-secondary small fw-bold">Rp <?= number_format($comm['amount_calculated'], 0, ',', '.') ?></span>
                                </td>
                                <td class="text-end">
                                    <span class="text-dark fw-bold">Rp <?= number_format($comm['amount_final'], 0, ',', '.') ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if($comm['status'] == 'paid'): ?>
                                        <span class="badge bg-success-soft text-success rounded-pill px-3">
                                            <i class="bi bi-check-circle-fill me-1"></i> DIBAYAR
                                        </span>
                                        <div class="smaller text-muted mt-1" style="font-size: 0.65rem;">
                                            <?= date('d/m/y H:i', strtotime($comm['paid_at'])) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-warning-soft text-warning rounded-pill px-3">
                                            <i class="bi bi-clock-fill me-1"></i> PENDING
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold border shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?= $comm['id'] ?>">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
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

<!-- Modals rendered outside the table to prevent flickering and focus issues -->
<?php if(!empty($commissions)): ?>
    <?php foreach($commissions as $comm): ?>
    <div class="modal fade" id="editModal<?= $comm['id'] ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Update Status Komisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('owner/commissions/update/' . $comm['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body py-4">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Informasi Pembayaran</label>
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary small">Agensi:</span>
                                    <span class="fw-bold small text-dark"><?= esc($comm['agency_name']) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary small">Paket:</span>
                                    <span class="fw-bold small text-dark"><?= esc($comm['package_name']) ?></span>
                                </div>
                                <div class="d-flex justify-content-between pt-2 border-top">
                                    <span class="text-secondary small">Estimasi Sistem:</span>
                                    <span class="fw-bold small text-dark">Rp <?= number_format($comm['amount_calculated'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nominal Akhir (Disetujui)</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light">Rp</span>
                                <input type="number" step="any" name="amount_final" class="form-control border-0 bg-light" value="<?= $comm['amount_final'] ?>" required>
                            </div>
                            <small class="text-muted">Masukkan nominal final yang akan/sudah dibayarkan.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Catatan / Bonus / Potongan</label>
                            <textarea name="notes" class="form-control border-0 bg-light" rows="2" placeholder="Contoh: Bonus target tercapai - Rp 500k"><?= esc($comm['notes']) ?></textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold small">Status Pembayaran</label>
                            <select name="status" class="form-select border-0 bg-light" required>
                                <option value="pending" <?= $comm['status'] == 'pending' ? 'selected' : '' ?>>Pending (Belum Dibayar)</option>
                                <option value="paid" <?= $comm['status'] == 'paid' ? 'selected' : '' ?>>Paid (Sudah Dibayar)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<style>
    .bg-primary-soft { background: rgba(13, 110, 253, 0.1); }
    .text-primary { color: #0d6efd !important; }
    .bg-success-soft { background: rgba(25, 135, 84, 0.1); }
    .text-success { color: #198754 !important; }
    .bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
    .text-warning { color: #ffc107 !important; }
    .smaller { font-size: 0.8rem; }
    .fw-800 { font-weight: 800; }
</style>

<?= $this->endSection() ?>
