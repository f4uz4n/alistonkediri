<?php
// force_packages_and_sidebar.php

// 1. Force write app/Views/agency/packages.php with Detail button
$packages_content = <<<'EOD'
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Paket Perjalanan</h2>
        <p class="text-secondary mb-0">Pilih paket terbaik untuk calon jamaah Anda</p>
    </div>
</div>

<div class="row g-4">
    <?php if(empty($packages)): ?>
        <div class="col-12 text-center py-5">
            <div class="py-5">
                <i class="bi bi-inbox text-muted fs-1 mb-3"></i>
                <p class="text-secondary">Belum ada paket perjalanan yang tersedia saat ini.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach($packages as $package): ?>
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden bg-white">
                <div class="position-relative">
                    <?php if($package['image']): ?>
                        <div style="height: 200px; background: url('<?= base_url($package['image']) ?>') center/cover no-repeat;"></div>
                    <?php else: ?>
                        <div style="height: 100px; background: linear-gradient(135deg, var(--primary-color) 0%, #ff7675 100%);"></div>
                    <?php endif; ?>
                    
                    <div class="position-absolute top-100 start-0 translate-middle-y ps-4 w-100 d-flex justify-content-between pe-4">
                        <div class="bg-white rounded-circle shadow-sm p-3 border d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <img src="<?= get_company_logo() ?>" alt="Logo" style="width: 35px;">
                        </div>
                        <div class="bg-white rounded-4 shadow-sm px-4 py-2 border">
                            <h3 class="fw-800 text-primary mb-0"><?= esc($package['price']) ?><small class="fs-6 ms-1"><?= esc($package['price_unit']) ?></small></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 pt-5 mt-2">
                    <h5 class="fw-800 text-dark mb-3"><?= esc($package['name']) ?></h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <small class="text-secondary d-block"><i class="bi bi-calendar-event me-1"></i> Keberangkatan</small>
                            <span class="fw-bold text-dark"><?= date('d M Y', strtotime($package['departure_date'])) ?></span>
                        </div>
                        <div class="col-6">
                            <small class="text-secondary d-block"><i class="bi bi-clock-history me-1"></i> Durasi</small>
                            <span class="fw-bold text-dark"><?= esc($package['duration']) ?></span>
                        </div>
                    </div>

                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <small class="text-secondary d-block">Mekkah</small>
                                <span class="fw-bold text-dark small"><?= esc($package['hotel_mekkah']) ?></span>
                            </div>
                            <div class="col-6">
                                <small class="text-secondary d-block">Madinah</small>
                                <span class="fw-bold text-dark small"><?= esc($package['hotel_madinah']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="<?= base_url('agency/package-detail/'.$package['id']) ?>" class="btn btn-light rounded-pill py-3 flex-grow-1 fw-bold border">
                            <i class="bi bi-info-circle me-2"></i> Detail
                        </a>
                        <a href="<?= base_url('agency/register/'.$package['id']) ?>" class="btn-premium flex-grow-1 rounded-pill py-3 text-center d-block">
                            <i class="bi bi-person-plus-fill me-2"></i> Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
EOD;

file_put_contents('app/Views/agency/packages.php', $packages_content);

// 2. Fix main.php sidebar for agency (definitively)
$main_file = 'app/Views/layouts/main.php';
$main_content = file_get_contents($main_file);

if (strpos($main_content, 'agency/packages') === false) {
    if (strpos($main_content, 'agency/team') !== false) {
        $replacement = <<<'EOD'
                <li class="nav-item">
                    <a href="<?= base_url('agency/packages') ?>" class="nav-link <?= strpos(current_url(), 'packages') !== false ? 'active' : '' ?>">
                        <i class="bi bi-airplane-fill"></i>
                        <span>Paket Travel</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('agency/team') ?>"
EOD;
        $new_main = str_replace('<li class="nav-item">' . "\n" . '                    <a href="<?= base_url(\'agency/team\') ?>"', $replacement, $main_content);
        file_put_contents($main_file, $new_main);
    }
}

echo "Packages View and Sidebar link force-applied.\n";
