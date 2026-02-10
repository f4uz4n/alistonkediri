<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 col-lg-8">
        <a href="<?= base_url('agency/tabungan') ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-3"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        <h2 class="fw-800 text-dark mb-1">Tambah Setoran (Transfer)</h2>
        <p class="text-secondary mb-2"><?= esc($saving['name']) ?></p>
        <p class="text-secondary mb-4">Saldo saat ini (hanya setoran terverifikasi): <strong class="text-primary">Rp <?= number_format($saving['total_balance'], 0, ',', '.') ?></strong></p>

        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-4 mb-3"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-4 mb-3"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): $err = session()->getFlashdata('errors'); ?>
            <div class="alert alert-danger border-0 rounded-4 mb-3">
                <ul class="mb-0 list-unstyled"><?php foreach ($err as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('agency/tabungan/store-deposit') ?>" method="post" enctype="multipart/form-data" class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <?= csrf_field() ?>
            <input type="hidden" name="travel_saving_id" value="<?= (int)$saving['id'] ?>">
            <p class="small text-secondary mb-3">Setoran akan berstatus <strong>Menunggu verifikasi admin</strong>. Unggah bukti transfer agar admin dapat memverifikasi.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jumlah (Rp) *</label>
                    <?php $oldAmount = old('amount'); $amtVal = $oldAmount !== null && $oldAmount !== '' ? preg_replace('/\D/', '', $oldAmount) : ''; ?>
                    <input type="text" name="amount" class="form-control form-control-lg bg-light border-0 format-rupiah" required placeholder="5.000.000" data-value="<?= esc($amtVal) ?>" value="<?= $amtVal !== '' ? number_format((int)$amtVal, 0, '', '.') : '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tanggal Transfer *</label>
                    <input type="date" name="payment_date" class="form-control form-control-lg bg-light border-0" required value="<?= esc(old('payment_date', date('Y-m-d'))) ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Bukti transfer (disarankan)</label>
                <input type="file" name="proof" class="form-control bg-light border-0" accept="image/*,.pdf">
                <small class="text-muted">Gambar atau PDF, max 5 MB. Mempercepat verifikasi admin.</small>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Catatan</label>
                <textarea name="notes" class="form-control bg-light border-0" rows="2" placeholder="Nomor rekening / keterangan"><?= esc(old('notes')) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold"><i class="bi bi-plus-circle me-2"></i>Kirim Setoran</button>
        </form>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3">Riwayat Setoran</h5>
            <?php if (empty($deposits)): ?>
                <p class="text-secondary small mb-0">Belum ada setoran.</p>
            <?php else: ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($deposits as $d): ?>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <span class="small"><?= date('d/m/Y', strtotime($d['payment_date'])) ?></span>
                                <strong class="d-block">Rp <?= number_format($d['amount'], 0, ',', '.') ?></strong>
                                <?php if (($d['status'] ?? 'verified') === 'pending'): ?>
                                    <span class="badge bg-warning text-dark small">Menunggu verifikasi</span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success small">Terverifikasi</span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($d['proof'])): ?>
                                <a href="<?= base_url($d['proof']) ?>" target="_blank" class="btn btn-light btn-sm">Bukti</a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
