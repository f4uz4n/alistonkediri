<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-800 text-dark mb-1">Laporan Penghasilan</h2>
                <p class="text-secondary mb-0">Rincian pendapatan dari jamaah terverifikasi</p>
            </div>
            <a href="<?= base_url('agency') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle me-3">
                        <i class="bi bi-wallet2 text-white fs-2"></i>
                    </div>
                    <div>
                        <p class="text-white text-opacity-75 text-uppercase small fw-bold ls-1 mb-0">Total Penghasilan</p>
                        <h2 class="fw-800 text-white mb-0">Rp <?= number_format($total_income, 0, ',', '.') ?></h2>
                    </div>
                </div>
                <i class="bi bi-currency-dollar position-absolute text-white opacity-10" style="font-size: 10rem; right: -30px; top: -30px;"></i>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-4 px-4">
                <h5 class="fw-bold text-dark mb-0">Riwayat Transaksi Masuk</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small text-uppercase">Tanggal Verifikasi</th>
                                <th class="py-3 text-secondary small text-uppercase">Nama Jamaah</th>
                                <th class="py-3 text-secondary small text-uppercase">Paket</th>
                                <th class="py-3 text-secondary small text-uppercase">Status</th>
                                <th class="pe-4 py-3 text-end text-secondary small text-uppercase">Nilai (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($participants)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <div class="py-3">
                                            <i class="bi bi-receipt text-secondary opacity-25 display-4 d-block mb-2"></i>
                                            Belum ada data penghasilan.
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($participants as $p): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">
                                        <?= date('d M Y', strtotime($p['updated_at'])) ?>
                                        <br>
                                        <small class="fw-normal"><?= date('H:i', strtotime($p['updated_at'])) ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block"><?= esc($p['name']) ?></span>
                                                <small class="text-secondary"><?= esc($p['nik']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= esc($p['package_name']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                            <i class="bi bi-check-circle-fill me-1"></i> Terverifikasi
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end fw-bold text-dark">
                                        Rp <?= number_format($p['price'], 0, ',', '.') ?>
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
