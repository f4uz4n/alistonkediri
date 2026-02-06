<?php
// fix_participants_view.php

$view_file = 'app/Views/agency/participants.php';
$content = <<<'EOD'
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Daftar Jamaah</h2>
        <p class="text-secondary mb-0">Kelola dan pantau status keberangkatan jamaah Anda</p>
    </div>
    <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
        <a href="<?= base_url('agency/packages') ?>" class="btn-premium px-4 py-3 rounded-pill d-inline-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i> Daftarkan Jamaah Baru
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 border-0 text-secondary small fw-bold text-uppercase">Jamaah</th>
                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase">Paket & NIK</th>
                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase">Kontak</th>
                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase">Status</th>
                        <th class="py-3 border-0 text-secondary small fw-bold text-uppercase">Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($participants)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-people text-muted fs-1 opacity-25 mb-3 d-block"></i>
                                    <p class="text-secondary mb-0">Belum ada jamaah yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($participants as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><?= esc($p['name']) ?></h6>
                                        <small class="text-secondary"><?= esc($p['gender'] ?? '-') ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark d-block"><?= esc($p['package_name']) ?></span>
                                <small class="text-secondary">NIK: <?= esc($p['nik']) ?></small>
                            </td>
                            <td>
                                <a href="https://wa.me/<?= str_replace(['+', '-', ' '], '', $p['phone']) ?>" target="_blank" class="text-decoration-none text-success fw-bold">
                                    <i class="bi bi-whatsapp me-1"></i> <?= esc($p['phone']) ?>
                                </a>
                            </td>
                            <td>
                                <?php if($p['status'] == 'pending'): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu Verifikasi
                                    </span>
                                <?php elseif($p['status'] == 'verified'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Terverifikasi
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                        <i class="bi bi-x-circle-fill me-1"></i> Dibatalkan
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-secondary"><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></small>
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
EOD;

file_put_contents($view_file, $content);
echo "View file $view_file written Successfully, size: " . filesize($view_file) . " bytes\n";
?>
EOD;
