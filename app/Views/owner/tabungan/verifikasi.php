<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $pending = $pending_deposits ?? []; ?>

<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Verifikasi Tabungan Jamaah</h2>
        <p class="text-secondary mb-0">Setoran dari agency yang menunggu verifikasi. Verifikasi agar saldo masuk ke tabungan jamaah.</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <a href="<?= base_url('owner/tabungan') ?>" class="btn btn-light border rounded-pill px-4 fw-bold"><i class="bi bi-arrow-left me-2"></i>Daftar Tabungan</a>
    </div>
</div>

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
                            <a href="<?= base_url('owner/tabungan/edit-deposit/' . $d['id']) ?>" class="btn btn-outline-info btn-sm rounded-pill me-1" title="Edit"><i class="bi bi-pencil-square"></i></a>
                            <form action="<?= base_url('owner/tabungan/delete-deposit/' . $d['id']) ?>" method="post" class="d-inline me-1" onsubmit="return confirm('Yakin hapus setoran ini?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                            <form action="<?= base_url('owner/tabungan/verify-deposit/' . $d['id']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
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

<?= $this->endSection() ?>
