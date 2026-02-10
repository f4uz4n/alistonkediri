<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-800 text-dark mb-1">Laporan Penghasilan</h2>
                <p class="text-secondary mb-0">Perolehan komisi setelah diverifikasi oleh admin</p>
            </div>
            <a href="<?= base_url('agency') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-3">Filter</h6>
                <form action="<?= base_url('agency/income') ?>" method="get" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control bg-light border-0" value="<?= esc($filters['start_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control bg-light border-0" value="<?= esc($filters['end_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Paket</label>
                        <select name="package_id" class="form-select bg-light border-0">
                            <option value="">Semua Paket</option>
                            <?php foreach ($packages as $pkg): ?>
                                <option value="<?= $pkg['id'] ?>" <?= (isset($filters['package_id']) && $filters['package_id'] === (string)$pkg['id']) ? 'selected' : '' ?>><?= esc($pkg['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold w-100">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden h-100">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle me-3">
                                <i class="bi bi-wallet2 text-white fs-2"></i>
                            </div>
                            <div>
                                <p class="text-white text-opacity-75 text-uppercase small fw-bold ls-1 mb-0">Sudah Dibayarkan</p>
                                <h2 class="fw-800 text-white mb-0">Rp <?= number_format($total_income, 0, ',', '.') ?></h2>
                            </div>
                        </div>
                        <i class="bi bi-currency-dollar position-absolute text-white opacity-10" style="font-size: 10rem; right: -30px; top: -30px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 border h-100">
                    <div class="card-body p-4">
                        <p class="text-secondary text-uppercase small fw-bold ls-1 mb-1">Belum Dibayarkan</p>
                        <h4 class="fw-800 text-dark mb-0">Rp <?= number_format($total_pending ?? 0, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-4 px-4">
                <h5 class="fw-bold text-dark mb-0">Daftar Komisi</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small text-uppercase">Tanggal Verifikasi</th>
                                <th class="py-3 text-secondary small text-uppercase">Paket</th>
                                <th class="py-3 text-secondary small text-uppercase">Jadwal Berangkat</th>
                                <th class="py-3 text-secondary small text-uppercase">Status</th>
                                <th class="pe-4 py-3 text-end text-secondary small text-uppercase">Nominal Komisi (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($commissions)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <div class="py-3">
                                            <i class="bi bi-receipt text-secondary opacity-25 display-4 d-block mb-2"></i>
                                            Belum ada data komisi.
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($commissions as $c): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">
                                        <?= !empty($c['paid_at']) ? date('d M Y', strtotime($c['paid_at'])) : '—' ?>
                                        <?php if (!empty($c['paid_at'])): ?>
                                        <br><small class="fw-normal"><?= date('H:i', strtotime($c['paid_at'])) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= esc($c['package_name']) ?></span>
                                    </td>
                                    <td>
                                        <?= !empty($c['departure_date']) ? date('d M Y', strtotime($c['departure_date'])) : '—' ?>
                                    </td>
                                    <td>
                                        <?php if (($c['status'] ?? '') === 'paid'): ?>
                                        <span class="badge bg-success rounded-pill">Sudah Dibayarkan</span>
                                        <?php else: ?>
                                        <span class="badge bg-warning text-dark rounded-pill">Belum Dibayarkan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end fw-bold text-dark">
                                        Rp <?= number_format((float)$c['amount_final'], 0, ',', '.') ?>
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
