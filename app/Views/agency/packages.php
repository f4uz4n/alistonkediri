<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php helper('package'); ?>
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
        <?php foreach($packages as $package):
            $depDate = isset($package['departure_date']) ? substr((string)$package['departure_date'], 0, 10) : '';
            $isExpired = ($depDate !== '' && $depDate < date('Y-m-d'));
            $kuotaPenuh = empty($package['is_active']);
            $canRegister = !$isExpired && !$kuotaPenuh;
        ?>
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden bg-white <?= !$canRegister ? 'opacity-90' : '' ?>">
                <div class="position-relative">
                    <?php if($package['image']): ?>
                        <div style="height: 200px; background: url('<?= base_url($package['image']) ?>') center/cover no-repeat;"></div>
                    <?php else: ?>
                        <div style="height: 200px; background: linear-gradient(135deg, var(--primary-color) 0%, #ff7675 100%);"></div>
                    <?php endif; ?>
                    <?php if ($isExpired): ?>
                        <span class="position-absolute top-0 end-0 m-2 badge bg-dark rounded-pill px-3 py-2 shadow-sm">Expired</span>
                    <?php elseif ($kuotaPenuh): ?>
                        <span class="position-absolute top-0 end-0 m-2 badge bg-secondary rounded-pill px-3 py-2 shadow-sm">Kuota Penuh</span>
                    <?php endif; ?>
                    <div class="position-absolute top-100 start-0 translate-middle-y ps-4 w-100 d-flex justify-content-between pe-4">
                        <div class="bg-white rounded-circle shadow-sm p-3 border d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <img src="<?= get_company_logo() ?>" alt="Logo" style="width: 35px;">
                        </div>
                        <div class="bg-white rounded-4 shadow-sm px-4 py-2 border">
                            <h3 class="fw-800 text-primary mb-0"><?= format_price_display($package['price']) ?></h3>
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
                                <?php $d1 = $package['display_hotel_1'] ?? null; ?>
                                <small class="text-secondary d-block"><?= $d1 && $d1['city'] ? esc($d1['city']) : esc($city1_name ?? 'Kota 1') ?></small>
                                <span class="fw-bold text-dark small"><?= $d1 ? esc($d1['name']) : esc($package['hotel_mekkah'] ?? '—') ?></span>
                            </div>
                            <div class="col-6">
                                <?php $d2 = $package['display_hotel_2'] ?? null; ?>
                                <small class="text-secondary d-block"><?= $d2 && $d2['city'] ? esc($d2['city']) : esc($city2_name ?? 'Kota 2') ?></small>
                                <span class="fw-bold text-dark small"><?= $d2 ? esc($d2['name']) : esc($package['hotel_madinah'] ?? '—') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="<?= base_url('agency/package-detail/'.$package['id']) ?>" class="btn btn-light rounded-pill py-3 flex-grow-1 fw-bold border">
                            <i class="bi bi-info-circle me-2"></i> Detail
                        </a>
                        <?php if ($canRegister): ?>
                            <a href="<?= base_url('agency/register/'.$package['id']) ?>" class="btn-premium flex-grow-1 rounded-pill py-3 text-center d-block">
                                <i class="bi bi-person-plus-fill me-2"></i> Daftar
                            </a>
                        <?php else: ?>
                            <span class="btn btn-secondary flex-grow-1 rounded-pill py-3 text-center d-block fw-bold opacity-75" style="cursor: not-allowed;" title="<?= $isExpired ? 'Paket sudah expired' : 'Kuota penuh' ?>">
                                <i class="bi bi-person-plus-fill me-2"></i> Daftar
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>