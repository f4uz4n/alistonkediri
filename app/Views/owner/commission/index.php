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
                <form action="<?= base_url('owner/commissions') ?>" method="get" class="commission-filter-form">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-secondary">Cari Agensi / Paket</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-search text-secondary"></i></span>
                                <input type="text" name="search" class="form-control bg-light border-0" placeholder="Nama agensi atau paket..." value="<?= esc($search ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-secondary">Paket</label>
                            <select name="package_id" class="form-select bg-light border-0 text-dark">
                                <option value="">Semua Paket</option>
                                <?php foreach($packages as $pkg): ?>
                                    <option value="<?= $pkg['id'] ?>" <?= ($package_id ?? '') == $pkg['id'] ? 'selected' : '' ?>><?= esc($pkg['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-secondary">Tgl. Berangkat</label>
                            <select name="departure_date" class="form-select bg-light border-0 text-dark">
                                <option value="">Semua Jadwal</option>
                                <?php foreach($departure_dates ?? [] as $dd): ?>
                                    <option value="<?= esc($dd['departure_date']) ?>" <?= ($departure_date ?? '') == $dd['departure_date'] ? 'selected' : '' ?>><?= date('d/m/Y', strtotime($dd['departure_date'])) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-secondary">Rentang Tanggal (Dibuat)</label>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <input type="date" name="start_date" class="form-control bg-light border-0 flex-grow-1" style="min-width: 140px;" value="<?= esc($start_date ?? '') ?>">
                                <span class="text-secondary">s/d</span>
                                <input type="date" name="end_date" class="form-control bg-light border-0 flex-grow-1" style="min-width: 140px;" value="<?= esc($end_date ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 d-flex gap-2 flex-shrink-0">
                            <a href="<?= base_url('owner/commissions') ?>" class="btn btn-light rounded-pill px-4 fw-bold border">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-funnel me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Modal verifikasi per jadwal (satu modal per tanggal yang punya pending)
if (!empty($pending_by_departure)):
    foreach ($pending_by_departure as $depDate => $pendingList):
        $modalId = 'bulkVerify' . preg_replace('/\D/', '', $depDate);
?>
<div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Verifikasi Komisi per Jadwal Berangkat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('owner/commissions/verify-by-departure') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="departure_date" value="<?= esc($depDate) ?>">
                <div class="modal-body py-4">
                    <p class="text-secondary small mb-3">Jadwal: <strong><?= date('d F Y', strtotime($depDate)) ?></strong>. Centang komisi yang sudah dibayar, atau biarkan semua tercentang untuk menandai semuanya.</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2"><input type="checkbox" class="form-check-input bulk-check-all" checked></th>
                                    <th class="py-2 small fw-bold">Agensi</th>
                                    <th class="py-2 small fw-bold">Paket</th>
                                    <th class="py-2 small fw-bold text-end">Komisi Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingList as $row): ?>
                                <tr>
                                    <td><input type="checkbox" name="commission_ids[]" value="<?= $row['id'] ?>" class="form-check-input bulk-check-item" checked></td>
                                    <td class="small"><?= esc($row['agency_name']) ?></td>
                                    <td class="small"><?= esc($row['package_name']) ?></td>
                                    <td class="text-end fw-bold small">Rp <?= number_format($row['amount_final'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small">Catatan (opsional)</label>
                        <textarea name="notes" class="form-control border-0 bg-light" rows="2" placeholder="Contoh: Transfer batch 1 Maret 2026"><?= esc($pendingList[0]['notes'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-all me-1"></i> Tandai yang dipilih sudah dibayar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    endforeach;
endif;
?>

<?php
// Modal rincian agency & komisi per jadwal (verifikasi global)
if (!empty($commissions_by_departure)):
    foreach ($commissions_by_departure as $depDate => $detailList):
        $detailModalId = 'detailModal' . preg_replace('/\D/', '', $depDate);
?>
<div class="modal fade" id="<?= $detailModalId ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Rincian Komisi per Jadwal — <?= date('d F Y', strtotime($depDate)) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-secondary small mb-3">Daftar agency beserta komisi yang diverifikasi secara global untuk jadwal pemberangkatan ini.</p>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-2 small fw-bold">Agensi</th>
                                <th class="py-2 small fw-bold">Paket</th>
                                <th class="py-2 small fw-bold text-end">Komisi Final</th>
                                <th class="py-2 small fw-bold text-center">Status</th>
                                <th class="pe-2 py-2 small fw-bold text-center">Tanggal Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detailList as $row): ?>
                            <tr>
                                <td class="small fw-bold"><?= esc($row['agency_name']) ?></td>
                                <td class="small"><?= esc($row['package_name']) ?></td>
                                <td class="text-end fw-bold small">Rp <?= number_format($row['amount_final'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <?php if (($row['status'] ?? '') === 'paid'): ?>
                                        <span class="badge bg-success-soft text-success rounded-pill">Dibayar</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-soft text-warning rounded-pill">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center small">
                                    <?php if (!empty($row['paid_at'])): ?>
                                        <?= date('d/m/Y H:i', strtotime($row['paid_at'])) ?>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php
    endforeach;
endif;
?>
<?php
$commissions_pending = array_filter($commissions ?? [], function($c) { return ($c['status'] ?? '') === 'pending'; });
$commissions_paid   = array_filter($commissions ?? [], function($c) { return ($c['status'] ?? '') === 'paid'; });
$count_pending = count($commissions_pending);
$count_paid    = count($commissions_paid);
?>
<script>
document.querySelectorAll('.bulk-check-all').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        var modal = this.closest('.modal');
        var items = modal ? modal.querySelectorAll('.bulk-check-item') : [];
        items.forEach(function(item) { item.checked = checkbox.checked; });
    });
});
</script>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-tabs-commission border-0 px-4 pt-3 bg-light" id="commissionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-top fw-bold px-4 py-3 <?= ($count_pending > 0 || $count_paid == 0) ? 'active' : '' ?>" id="tab-pending" data-bs-toggle="tab" data-bs-target="#pane-pending" type="button" role="tab">
                            <i class="bi bi-clock-history me-2"></i>Belum terbayar
                            <span class="badge bg-warning ms-2"><?= $count_pending ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-top fw-bold px-4 py-3 <?= $count_pending == 0 && $count_paid > 0 ? 'active' : '' ?>" id="tab-paid" data-bs-toggle="tab" data-bs-target="#pane-paid" type="button" role="tab">
                            <i class="bi bi-check-circle me-2"></i>Sudah terbayar
                            <span class="badge bg-success ms-2"><?= $count_paid ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-top fw-bold px-4 py-3" id="tab-riwayat" data-bs-toggle="tab" data-bs-target="#pane-riwayat" type="button" role="tab">
                            <i class="bi bi-journal-bookmark me-2"></i>Riwayat
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-4" id="commissionTabsContent">
                    <!-- Tab Belum terbayar -->
                    <div class="tab-pane fade <?= ($count_pending > 0 || $count_paid == 0) ? 'show active' : '' ?>" id="pane-pending" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 border-0 text-secondary small fw-bold text-uppercase">Agensi & Paket</th>
                                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Tgl. Berangkat</th>
                                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Total Pax</th>
                                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Komisi Sistem</th>
                                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Komisi Final</th>
                                        <th class="pe-4 py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($commissions_pending)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="py-4">
                                                    <i class="bi bi-check-circle text-muted fs-1 mb-3"></i>
                                                    <p class="text-secondary mb-0">Tidak ada komisi belum terbayar.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($commissions_pending as $comm): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark d-block"><?= esc($comm['agency_name']) ?></span>
                                                    <small class="text-secondary"><i class="bi bi-airplane-fill me-1"></i> <?= esc($comm['package_name']) ?></small>
                                                </div>
                                            </td>
                                            <td class="text-center small"><?= !empty($comm['departure_date']) ? date('d/m/Y', strtotime($comm['departure_date'])) : '—' ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary-soft text-primary rounded-pill px-3"><?= $comm['rate'] > 0 ? round($comm['amount_calculated'] / $comm['rate']) : 0 ?> Pax</span>
                                            </td>
                                            <td class="text-end"><span class="text-secondary small fw-bold">Rp <?= number_format($comm['amount_calculated'], 0, ',', '.') ?></span></td>
                                            <td class="text-end"><span class="text-dark fw-bold">Rp <?= number_format($comm['amount_final'], 0, ',', '.') ?></span></td>
                                            <td class="pe-4 text-end">
                                                <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold border shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $comm['id'] ?>">
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
                    <!-- Tab Sudah terbayar -->
                    <div class="tab-pane fade <?= $count_pending == 0 && $count_paid > 0 ? 'show active' : '' ?>" id="pane-paid" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 border-0 text-secondary small fw-bold text-uppercase">Agensi & Paket</th>
                                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Tgl. Berangkat</th>
                                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Total Pax</th>
                                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Komisi Final</th>
                                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Dibayar</th>
                                        <th class="pe-4 py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($commissions_paid)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="py-4">
                                                    <i class="bi bi-wallet2 text-muted fs-1 mb-3"></i>
                                                    <p class="text-secondary mb-0">Belum ada komisi yang sudah dibayar.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($commissions_paid as $comm): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark d-block"><?= esc($comm['agency_name']) ?></span>
                                                    <small class="text-secondary"><i class="bi bi-airplane-fill me-1"></i> <?= esc($comm['package_name']) ?></small>
                                                </div>
                                            </td>
                                            <td class="text-center small"><?= !empty($comm['departure_date']) ? date('d/m/Y', strtotime($comm['departure_date'])) : '—' ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary-soft text-primary rounded-pill px-3"><?= $comm['rate'] > 0 ? round($comm['amount_calculated'] / $comm['rate']) : 0 ?> Pax</span>
                                            </td>
                                            <td class="text-end"><span class="text-dark fw-bold">Rp <?= number_format($comm['amount_final'], 0, ',', '.') ?></span></td>
                                            <td class="text-center small text-muted"><?= !empty($comm['paid_at']) ? date('d/m/Y H:i', strtotime($comm['paid_at'])) : '—' ?></td>
                                            <td class="pe-4 text-end">
                                                <a href="<?= base_url('owner/commissions/print/' . $comm['id']) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold me-1" title="Cetak slip komisi">
                                                    <i class="bi bi-printer-fill me-1"></i> Cetak
                                                </a>
                                                <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold border shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $comm['id'] ?>">
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
                    <!-- Tab Riwayat: verifikasi global per tanggal pemberangkatan + rincian jadwal -->
                    <div class="tab-pane fade" id="pane-riwayat" role="tabpanel">
                        <p class="text-secondary small mb-3">Verifikasi pengambilan komisi secara global per tanggal pemberangkatan. Setelah verifikasi, komisi hilang dari tab <strong>Belum terbayar</strong> dan muncul di <strong>Sudah terbayar</strong>.</p>
                        <?php if (!empty($summary_by_departure)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-2 small fw-bold text-secondary">Tanggal Berangkat</th>
                                        <th class="py-2 small fw-bold text-secondary text-center">Jumlah</th>
                                        <th class="py-2 small fw-bold text-secondary text-end">Total Komisi</th>
                                        <th class="py-2 small fw-bold text-secondary text-center">Tanggal Verifikasi</th>
                                        <th class="py-2 small fw-bold text-secondary text-center">Status</th>
                                        <th class="pe-4 py-2 small fw-bold text-secondary text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($summary_by_departure as $sum): ?>
                                    <?php $detailModalId = 'detailModal' . preg_replace('/\D/', '', $sum['departure_date']); ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?= date('d M Y', strtotime($sum['departure_date'])) ?></td>
                                        <td class="text-center"><?= (int)$sum['total_rows'] ?></td>
                                        <td class="text-end fw-bold">Rp <?= number_format((float)$sum['total_commission'], 0, ',', '.') ?></td>
                                        <td class="text-center small">
                                            <?php if (!empty($sum['last_verified_at'])): ?>
                                                <?= date('d/m/Y H:i', strtotime($sum['last_verified_at'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ((int)$sum['pending_count'] > 0): ?>
                                                <span class="badge bg-warning-soft text-warning"><?= (int)$sum['pending_count'] ?> belum dibayar</span>
                                            <?php else: ?>
                                                <span class="badge bg-success-soft text-success">Semua dibayar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill me-1" data-bs-toggle="modal" data-bs-target="#<?= $detailModalId ?>">
                                                <i class="bi bi-list-ul me-1"></i> Rincian
                                            </button>
                                            <?php if ((int)$sum['pending_count'] > 0): ?>
                                                <?php $modalId = 'bulkVerify' . preg_replace('/\D/', '', $sum['departure_date']); ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                                                    <i class="bi bi-check2-all me-1"></i> Verifikasi Jadwal
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-calendar3 text-muted fs-1 mb-3"></i>
                            <p class="text-secondary mb-0">Belum ada data komisi per jadwal pemberangkatan.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
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
    .nav-tabs-commission .nav-link {
        border: none;
        color: #6c757d;
        background: transparent;
    }
    .nav-tabs-commission .nav-link:hover { color: #0d6efd; }
    .nav-tabs-commission .nav-link.active {
        color: #0d6efd;
        background: #fff;
        border-bottom: 2px solid #fff;
        margin-bottom: -1px;
    }
    .commission-filter-form .row.align-items-end { margin-top: 0; }
    .commission-filter-form input[type="date"] { min-width: 140px; }
    @media (min-width: 768px) {
        .commission-filter-form .col-md-4:last-of-type.d-flex { justify-content: flex-start; }
    }
</style>

<?= $this->endSection() ?>
