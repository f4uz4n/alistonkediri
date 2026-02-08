<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-8">
        <h2 class="fw-800 text-dark mb-1">Dashboard Agency</h2>
        <p class="text-secondary mb-0">Selamat datang kembali, <span class="fw-bold text-primary"><?= session()->get('username') ?></span>!</p>
    </div>
    <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
        <span class="badge bg-white text-secondary px-3 py-2 shadow-sm rounded-pill fw-normal">
            <i class="bi bi-calendar-event me-2 text-primary"></i> <?= date('d M Y') ?>
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                        <i class="bi bi-people-fill text-primary fs-3"></i>
                    </div>
                </div>
                <h2 class="fw-800 text-dark mb-1"><?= number_format($stats['total_jamaah']) ?></h2>
                <p class="text-secondary text-uppercase small fw-bold ls-1 mb-0">Total Jamaah</p>
                <i class="bi bi-people position-absolute text-primary opacity-10" style="font-size: 8rem; right: -20px; bottom: -20px;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="bg-success bg-opacity-10 p-3 rounded-4">
                        <i class="bi bi-check-circle-fill text-success fs-3"></i>
                    </div>
                </div>
                <h2 class="fw-800 text-dark mb-1"><?= number_format($stats['verified_jamaah']) ?></h2>
                <p class="text-secondary text-uppercase small fw-bold ls-1 mb-0">Jamaah Terverifikasi</p>
                <i class="bi bi-person-check position-absolute text-success opacity-10" style="font-size: 8rem; right: -20px; bottom: -20px;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-primary text-white">
            <div class="card-body p-4 position-relative">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="bg-white bg-opacity-25 p-3 rounded-4">
                        <i class="bi bi-wallet2 text-white fs-3"></i>
                    </div>
                    <a href="<?= base_url('agency/income') ?>" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">Detail</a>
                </div>
                <h2 class="fw-800 text-white mb-1">Rp <?= number_format($stats['total_income'], 0, ',', '.') ?></h2>
                <p class="text-white text-opacity-75 text-uppercase small fw-bold ls-1 mb-0">Komisi Terverifikasi</p>
                <i class="bi bi-currency-dollar position-absolute text-white opacity-10" style="font-size: 8rem; right: -20px; bottom: -20px;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Recent Participants -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">Jamaah Terverifikasi Terbaru</h5>
                <a href="<?= base_url('agency/participants') ?>" class="btn btn-sm btn-light rounded-pill fw-bold">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-secondary small text-uppercase">Nama</th>
                                <th class="text-secondary small text-uppercase">Paket</th>
                                <th class="text-secondary small text-uppercase">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($recent_participants)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada jamaah terverifikasi</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($recent_participants as $p): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 text-secondary">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark"><?= esc($p['name']) ?></h6>
                                                <small class="text-muted"><?= esc($p['nik']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= esc($p['package_name']) ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted fw-bold"><?= date('d M Y', strtotime($p['updated_at'])) ?></small>
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

    <!-- Quick Links / Shortcuts -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 px-4">
                <h5 class="fw-bold text-dark mb-0">Akses Cepat</h5>
            </div>
            <div class="card-body p-4">
                <a href="<?= base_url('agency/packages') ?>" class="d-flex align-items-center p-3 rounded-3 bg-light mb-3 text-decoration-none folder-hover">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                        <i class="bi bi-airplane-fill text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-dark fw-bold mb-0">Daftar Paket</h6>
                        <small class="text-secondary">Lihat paket tersedia</small>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </a>
                <a href="<?= base_url('agency/materials') ?>" class="d-flex align-items-center p-3 rounded-3 bg-light mb-3 text-decoration-none folder-hover">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                        <i class="bi bi-cloud-arrow-down-fill text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-dark fw-bold mb-0">Materi Promosi</h6>
                        <small class="text-secondary">Materi pemasaran dari kantor</small>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </a>
                <a href="<?= base_url('agency/payments') ?>" class="d-flex align-items-center p-3 rounded-3 bg-light mb-3 text-decoration-none folder-hover">
                    <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                        <i class="bi bi-wallet2 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-dark fw-bold mb-0">Laporan Pembayaran</h6>
                        <small class="text-secondary">Cek status pembayaran</small>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.folder-hover {
    transition: all 0.2s;
}
.folder-hover:hover {
    background-color: #fff !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transform: translateX(5px);
}
</style>
<?= $this->endSection() ?>
