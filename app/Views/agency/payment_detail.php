<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= base_url('agency/payments') ?>" class="text-primary text-decoration-none">Laporan Pembayaran</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Cicilan</li>
                    </ol>
                </nav>
                <h2 class="fw-800 text-dark mb-0">Jamaah: <?= esc($participant['name']) ?></h2>
            </div>
            <a href="<?= base_url('agency/payments') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <?php if(session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100">
                    <h6 class="text-white-50 small text-uppercase fw-bold mb-2">Sisa Tagihan</h6>
                    <h3 class="fw-800 mb-0">Rp <?= number_format((float)$participant['price'] - (float)$total_paid, 0, ',', '.') ?></h3>
                    <small class="text-white-50 mt-2">Dari total Rp <?= number_format((float)$participant['price'], 0, ',', '.') ?></small>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <h5 class="fw-bold text-dark mb-3">Lapor Cicilan Baru</h5>
                    <form action="<?= base_url('agency/store-payment') ?>" method="post" enctype="multipart/form-data" class="row g-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="participant_id" value="<?= $participant['id'] ?>">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nominal Bayar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">Rp</span>
                                <input type="number" name="amount" class="form-control bg-light border-0" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Bayar</label>
                            <input type="date" name="payment_date" class="form-control bg-light border-0" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Bukti Transfer (Image)</label>
                            <input type="file" name="proof" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Catatan (Optional)</label>
                            <input type="text" name="notes" class="form-control bg-light border-0" placeholder="cth: DP Awal / Cicilan 2">
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold">
                                <i class="bi bi-cloud-upload me-2"></i> Unggah Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-transparent border-0 py-4 px-4 border-bottom">
                <h5 class="fw-bold text-dark mb-0">Riwayat Pembayaran</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Nominal</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($installments)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-secondary italic">Belum ada riwayat cicilan</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($installments as $ins): ?>
                                <tr>
                                    <td class="ps-4"><?= date('d M Y', strtotime($ins['payment_date'])) ?></td>
                                    <td class="fw-bold">Rp <?= number_format((float)$ins['amount'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="<?= base_url($ins['proof']) ?>" target="_blank" class="text-decoration-none small fw-bold">
                                            <i class="bi bi-image me-1"></i> Lihat Bukti
                                        </a>
                                    </td>
                                    <td>
                                        <?php if($ins['status'] == 'pending'): ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill small">Proses Verifikasi</span>
                                        <?php elseif($ins['status'] == 'verified'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill small">Terverifikasi</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill small">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-secondary small"><?= esc($ins['notes']) ?></td>
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