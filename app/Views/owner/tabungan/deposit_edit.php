<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$dep = $deposit ?? [];
$s = $saving ?? [];
$savingId = (int)($s['id'] ?? 0);
$oldAmount = old('amount');
$amtVal = $oldAmount !== null && $oldAmount !== '' ? preg_replace('/\D/', '', $oldAmount) : (isset($dep['amount']) ? (string)(int)$dep['amount'] : '');
?>
<div class="row">
    <div class="col-12 col-lg-8">
        <a href="<?= base_url('owner/tabungan/deposit/' . $savingId) ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-3"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        <h2 class="fw-800 text-dark mb-1">Edit Setoran</h2>
        <p class="text-secondary mb-2"><?= esc($s['name']) ?> — <?= esc($s['agency_name']) ?></p>
        <p class="text-secondary mb-4">No. Setoran #<?= str_pad($dep['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?> · Status: <span class="badge bg-<?= ($dep['status'] ?? '') === 'verified' ? 'success' : 'warning text-dark' ?>"><?= ($dep['status'] ?? '') === 'verified' ? 'Terverifikasi' : 'Pending' ?></span></p>

        <?php if (session()->getFlashdata('errors')): $err = session()->getFlashdata('errors'); ?>
        <div class="alert alert-danger border-0 rounded-4 mb-3">
            <ul class="mb-0 list-unstyled"><?php foreach ($err as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('owner/tabungan/update-deposit/' . (int)($dep['id'] ?? 0)) ?>" method="post" enctype="multipart/form-data" class="card border-0 shadow-sm rounded-4 p-4">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jumlah (Rp) *</label>
                    <input type="text" name="amount" class="form-control form-control-lg bg-light border-0 format-rupiah" required placeholder="5.000.000" data-value="<?= esc($amtVal) ?>" value="<?= $amtVal !== '' ? number_format((int)$amtVal, 0, '', '.') : '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tanggal Setoran *</label>
                    <input type="date" name="payment_date" class="form-control form-control-lg bg-light border-0" required value="<?= esc(old('payment_date', $dep['payment_date'] ?? date('Y-m-d'))) ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Bukti transfer (opsional)</label>
                <input type="file" name="proof" class="form-control bg-light border-0" accept="image/*,.pdf">
                <?php if (!empty($dep['proof'])): ?>
                    <p class="small text-muted mt-1 mb-0">Bukti saat ini: <a href="<?= base_url($dep['proof']) ?>" target="_blank" rel="noopener">Lihat berkas</a>. Kosongkan jika tidak mengganti.</p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Catatan</label>
                <textarea name="notes" class="form-control bg-light border-0" rows="2"><?= esc(old('notes', $dep['notes'] ?? '')) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold"><i class="bi bi-check2 me-2"></i>Simpan Perubahan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
