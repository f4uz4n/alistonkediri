<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $activeTab = $activeTab ?? 'daftar'; $pending = $pending_deposits ?? []; ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Tabungan Perjalanan</h2>
        <p class="text-secondary mb-0">Jamaah menabung tanpa paket; klaim ke paket ketika saldo cukup</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <a href="<?= base_url('owner/tabungan/create') ?>" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-plus-lg me-2"></i>Tambah Jamaah Tabungan</a>
    </div>
</div>

<ul class="nav nav-tabs nav-tabs-card mb-4 border-0" id="tabunganTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link rounded-pill me-2 <?= $activeTab === 'daftar' ? 'active' : '' ?>" href="<?= base_url('owner/tabungan') ?>" role="tab">Daftar Tabungan</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link rounded-pill <?= $activeTab === 'verifikasi' ? 'active' : '' ?>" href="<?= base_url('owner/tabungan?tab=verifikasi') ?>" role="tab">Verifikasi <span class="badge bg-warning text-dark ms-1"><?= count($pending) ?></span></a>
    </li>
</ul>

<div class="tab-content" id="tabunganTabContent">
    <div class="tab-pane fade <?= $activeTab === 'daftar' ? 'show active' : '' ?>" role="tabpanel">
        <div class="row mb-3">
            <div class="col-12">
                <form action="<?= base_url('owner/tabungan') ?>" method="get" class="d-flex flex-wrap gap-2 align-items-center mb-3">
                    <?php if (($filterStatus ?? '') !== ''): ?><input type="hidden" name="status" value="<?= esc($filterStatus) ?>"><?php endif; ?>
                    <input type="text" name="cari" class="form-control form-control-sm" style="max-width: 220px;" placeholder="Cari NIK atau nama..." value="<?= esc($filterCari ?? '') ?>">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3"><i class="bi bi-search me-1"></i>Cari</button>
                    <?php if (($filterCari ?? '') !== ''): ?>
                    <a href="<?= base_url('owner/tabungan' . (($filterStatus ?? '') !== '' ? '?status=' . urlencode($filterStatus) : '')) ?>" class="btn btn-outline-secondary btn-sm rounded-pill">Reset</a>
                    <?php endif; ?>
                </form>
                <div class="btn-group rounded-pill border bg-white shadow-sm">
                    <a href="<?= base_url('owner/tabungan') ?>" class="btn btn-sm <?= ($filterStatus ?? '') === '' ? 'btn-primary' : 'btn-light' ?> rounded-pill">Semua</a>
                    <a href="<?= base_url('owner/tabungan?status=menabung') ?>" class="btn btn-sm <?= ($filterStatus ?? '') === 'menabung' ? 'btn-primary' : 'btn-light' ?> rounded-pill">Menabung</a>
                    <a href="<?= base_url('owner/tabungan?status=claimed') ?>" class="btn btn-sm <?= ($filterStatus ?? '') === 'claimed' ? 'btn-primary' : 'btn-light' ?> rounded-pill">Terklaim</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase">Nama</th>
                                    <th class="py-3 text-secondary small fw-bold text-uppercase">NIK</th>
                                    <th class="py-3 text-secondary small fw-bold text-uppercase">Agensi</th>
                                    <th class="py-3 text-secondary small fw-bold text-uppercase">Tanggal Daftar</th>
                                    <th class="py-3 text-secondary small fw-bold text-uppercase text-end">Saldo</th>
                                    <th class="py-3 text-secondary small fw-bold text-uppercase">Status</th>
                                    <th class="pe-4 py-3 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($savings)): ?>
                                <tr><td colspan="7" class="text-center py-5"><i class="bi bi-safe2 text-muted fs-1 d-block mb-3"></i><p class="text-secondary mb-0">Belum ada data tabungan.</p></td></tr>
                                <?php else: ?>
                                <?php foreach ($savings as $s): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?= esc($s['name']) ?></td>
                                    <td><?= esc($s['nik']) ?></td>
                                    <td><?= esc($s['agency_name'] ?? '-') ?></td>
                                    <td class="small"><?= !empty($s['created_at']) ? date('d M Y', strtotime($s['created_at'])) : '—' ?></td>
                                    <td class="text-end fw-bold">Rp <?= number_format($s['total_balance'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if (($s['status'] ?? '') === 'claimed'): ?>
                                        <span class="badge bg-success">Terklaim</span>
                                        <?php if (!empty($s['participant_id'])): ?><a href="<?= base_url('owner/participant/kelola/' . $s['participant_id']) ?>" class="badge bg-outline-secondary text-decoration-none ms-1">Ke Jamaah</a><?php endif; ?>
                                        <?php else: ?>
                                        <span class="badge bg-warning text-dark">Menabung</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <?php if (($s['status'] ?? '') === 'menabung'): ?>
                                        <a href="<?= base_url('owner/tabungan/deposit/' . $s['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill me-1">Setoran</a>
                                        <a href="<?= base_url('owner/tabungan/claim/' . $s['id']) ?>" class="btn btn-outline-success btn-sm rounded-pill">Klaim ke Paket</a>
                                        <?php else: ?>
                                        <a href="<?= base_url('owner/participant/kelola/' . $s['participant_id']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill">Lihat Jamaah</a>
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
    <div class="tab-pane fade <?= $activeTab === 'verifikasi' ? 'show active' : '' ?>" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase">Tanggal Setoran</th>
                                <th class="py-3 text-secondary small fw-bold text-uppercase">Nama Jamaah</th>
                                <th class="py-3 text-secondary small fw-bold text-uppercase">NIK</th>
                                <th class="py-3 text-secondary small fw-bold text-uppercase">Agensi</th>
                                <th class="py-3 text-secondary small fw-bold text-uppercase text-end">Jumlah</th>
                                <th class="py-3 text-secondary small fw-bold text-uppercase">Bukti</th>
                                <th class="pe-4 py-3 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pending)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-check-circle text-success display-4 d-block mb-2"></i>
                                    Tidak ada setoran yang menunggu verifikasi.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($pending as $d): ?>
                            <tr>
                                <td class="ps-4 small"><?= date('d M Y', strtotime($d['payment_date'])) ?></td>
                                <td class="fw-bold"><?= esc($d['saving_name'] ?? '-') ?></td>
                                <td><?= esc($d['saving_nik'] ?? '-') ?></td>
                                <td><?= esc($d['agency_name'] ?? '-') ?></td>
                                <td class="text-end fw-bold">Rp <?= number_format((float)$d['amount'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if (!empty($d['proof'])): ?>
                                    <a href="<?= base_url($d['proof']) ?>" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill">Lihat Bukti</a>
                                    <?php else: ?>
                                    <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <form action="<?= base_url('owner/tabungan/verify-deposit/' . $d['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="redirect_tab" value="verifikasi">
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">Verifikasi</button>
                                    </form>
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

<style>
.nav-tabs-card .nav-link { font-weight: 600; color: #6c757d; border: none; padding: 0.5rem 1rem; }
.nav-tabs-card .nav-link:hover { color: var(--primary, #0d6efd); }
.nav-tabs-card .nav-link.active { color: var(--primary); background: rgba(13, 110, 253, 0.1); }
</style>
<?= $this->endSection() ?>
