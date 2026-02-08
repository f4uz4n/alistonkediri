<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <a href="<?= base_url('owner/tabungan') ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-3"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        <h2 class="fw-800 text-dark mb-1">Klaim Tabungan ke Paket</h2>
        <p class="text-secondary mb-4">Pilih paket untuk mengalihkan jamaah tabungan menjadi peserta paket. Saldo tabungan akan dicatat sebagai pembayaran.</p>

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="text-secondary small text-uppercase fw-bold mb-2">Jamaah</h6>
            <p class="mb-1 fw-bold"><?= esc($saving['name']) ?></p>
            <p class="mb-1 small text-secondary">NIK: <?= esc($saving['nik']) ?> · <?= esc($saving['agency_name']) ?></p>
            <p class="mb-0"><span class="badge bg-primary fs-6">Saldo: Rp <?= number_format($totalBalance, 0, ',', '.') ?></span></p>
        </div>

        <form action="<?= base_url('owner/tabungan/do-claim') ?>" method="post" class="card border-0 shadow-sm rounded-4 p-4">
            <?= csrf_field() ?>
            <input type="hidden" name="travel_saving_id" value="<?= (int)$saving['id'] ?>">
            <div class="mb-4">
                <label class="form-label fw-bold">Pilih Paket <span class="text-danger">*</span></label>
                <select name="package_id" class="form-select form-select-lg bg-light border-0" required>
                    <option value="">— Pilih paket —</option>
                    <?php foreach ($packages ?? [] as $p): ?>
                        <option value="<?= (int)$p['id'] ?>" <?= (int)old('package_id') === (int)$p['id'] ? 'selected' : '' ?>>
                            <?= esc($p['name']) ?> — Rp <?= number_format($p['price'], 0, ',', '.') ?> (<?= date('d/m/Y', strtotime($p['departure_date'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="small text-secondary mt-2 mb-0">Saldo tabungan (Rp <?= number_format($totalBalance, 0, ',', '.') ?>) akan dicatat sebagai pembayaran verified. Kekurangan bisa dilunasi di halaman jamaah.</p>
            </div>
            <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold"><i class="bi bi-check2-circle me-2"></i>Klaim ke Paket</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
