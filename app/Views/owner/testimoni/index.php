<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row align-items-center mb-5">
    <div class="col-12">
        <h2 class="fw-800 text-dark mb-1">Testimoni Jamaah</h2>
        <p class="text-secondary mb-0">Verifikasi testimoni dari form publik dan agency sebelum dipublikasikan di halaman depan</p>
    </div>
</div>

<?php if (session()->getFlashdata('msg')): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="<?= base_url('owner/testimoni') ?>" method="get" class="row g-3 align-items-end">
            <div class="col-auto">
                <label class="form-label small fw-bold text-secondary mb-0">Status</label>
                <select name="status" class="form-select form-select-sm" style="width: auto;">
                    <option value="">Semua</option>
                    <option value="pending" <?= ($filter_status ?? '') === 'pending' ? 'selected' : '' ?>>Menunggu verifikasi</option>
                    <option value="verified" <?= ($filter_status ?? '') === 'verified' ? 'selected' : '' ?>>Sudah diverifikasi</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Tanggal</th>
                        <th class="py-3">Nama</th>
                        <th class="py-3">Rating</th>
                        <th class="py-3">Paket</th>
                        <th class="py-3">Sumber</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($testimonials)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada data testimoni.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($testimonials as $t): ?>
                        <tr>
                            <td class="ps-4 small"><?= date('d M Y H:i', strtotime($t['created_at'])) ?></td>
                            <td class="fw-bold"><?= esc($t['name']) ?></td>
                            <td><span class="text-warning" title="<?= (int)($t['rating'] ?? 0) ?> bintang"><?= str_repeat('★', (int)($t['rating'] ?? 5)) ?><?= str_repeat('☆', 5 - (int)($t['rating'] ?? 5)) ?></span></td>
                            <td><?= esc($t['package_name'] ?? '—') ?></td>
                            <td>
                                <?php if (($t['source'] ?? '') === 'agency'): ?>
                                    <span class="badge bg-info bg-opacity-10 text-info">Agency</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">Publik</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (($t['status'] ?? '') === 'verified'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Dipublikasikan</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="<?= base_url('owner/testimoni/edit/'.$t['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-1">Edit</a>
                                <?php if (($t['status'] ?? '') === 'pending'): ?>
                                    <form action="<?= base_url('owner/testimoni/verify/'.$t['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 me-1">Verifikasi</button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?= base_url('owner/testimoni/delete/'.$t['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <tr class="border-0">
                            <td colspan="7" class="ps-4 pt-0 pb-4 text-secondary small" style="vertical-align: top;">
                                <strong>Testimoni:</strong> <?= nl2br(esc($t['testimonial'])) ?>
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
