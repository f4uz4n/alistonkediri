<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php helper('package'); ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('agency/packages') ?>" class="text-decoration-none text-secondary">Paket Travel</a></li>
                <li class="breadcrumb-item active fw-bold" aria-current="page">Detail Paket</li>
            </ol>
        </nav>
        <h2 class="fw-800 text-dark mb-1"><?= esc($package['name']) ?></h2>
    </div>
    <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
        <a href="<?= base_url('agency/register/'.$package['id']) ?>" class="btn-premium px-5 py-3 rounded-pill d-inline-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i> Daftarkan Sekarang
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
            <?php if($package['image']): ?>
                <img src="<?= base_url($package['image']) ?>" alt="<?= esc($package['name']) ?>" class="img-fluid w-100" style="max-height: 450px; object-fit: cover;">
            <?php else: ?>
                <div class="p-5 text-center bg-light" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-image text-muted fs-1 opacity-25"></i>
                </div>
            <?php endif; ?>
            
            <div class="card-body p-4 p-md-5">
                <h4 class="fw-800 text-dark mb-4">Ringkasan Perjalanan</h4>
                <div class="row g-4 mb-5">
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 rounded-4 bg-light border">
                            <i class="bi bi-calendar-check fs-3 text-primary mb-2"></i>
                            <small class="text-secondary d-block text-uppercase fw-bold ls-1" style="font-size: 0.6rem;">Keberangkatan</small>
                            <span class="fw-bold text-dark small"><?= date('d M Y', strtotime($package['departure_date'])) ?></span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 rounded-4 bg-light border">
                            <i class="bi bi-clock-history fs-3 text-primary mb-2"></i>
                            <small class="text-secondary d-block text-uppercase fw-bold ls-1" style="font-size: 0.6rem;">Durasi</small>
                            <span class="fw-bold text-dark small"><?= esc($package['duration']) ?></span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 rounded-4 bg-light border">
                            <i class="bi bi-geo-alt-fill fs-3 text-primary mb-2"></i>
                            <small class="text-secondary d-block text-uppercase fw-bold ls-1" style="font-size: 0.6rem;">Rute</small>
                            <span class="fw-bold text-dark small"><?= esc($package['location_start_end']) ?></span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 rounded-4 bg-light border">
                            <i class="bi bi-airplane fs-3 text-primary mb-2"></i>
                            <small class="text-secondary d-block text-uppercase fw-bold ls-1" style="font-size: 0.6rem;">Maskapai</small>
                            <span class="fw-bold text-dark small"><?= esc($package['airline']) ?></span>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold text-dark mb-3">Fasilitas Termasuk:</h6>
                        <ul class="list-unstyled mb-0">
                            <?php 
                                $incs = json_decode($package['inclusions'], true);
                                if(is_array($incs)): 
                                    foreach($incs as $inc):
                            ?>
                                <li class="text-secondary mb-2 d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2 small"></i> <?= esc($inc) ?>
                                </li>
                            <?php 
                                    endforeach;
                                endif; 
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-3">Bonus & Freebies:</h6>
                        <ul class="list-unstyled mb-0">
                            <?php 
                                $frees = json_decode($package['freebies'], true);
                                if(is_array($frees)): 
                                    foreach($frees as $free):
                            ?>
                                <li class="text-secondary mb-2 d-flex align-items-center">
                                    <i class="bi bi-gift-fill text-warning me-2 small"></i> <?= esc($free) ?>
                                </li>
                            <?php 
                                    endforeach;
                                endif; 
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white mb-4 p-4 text-center overflow-hidden position-relative">
            <div class="position-absolute top-0 end-0 opacity-10" style="transform: translate(20%, -20%)">
                <i class="bi bi-tags-fill" style="font-size: 10rem;"></i>
            </div>
            <h6 class="text-white-50 fw-bold text-uppercase ls-2 mb-2">Harga Paket Mulai Dari</h6>
            <h1 class="fw-800 mb-2 display-6"><?= format_price_display($package['price']) ?></h1>
            <p class="mb-0 text-white-50 small">*Sesuai syarat dan ketentuan berlaku</p>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="bi bi-building-fill me-2 text-primary"></i>Akomodasi Hotel</h5>
            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                <div class="bg-white rounded-circle p-3 shadow-sm me-3">
                    <i class="bi bi-stars text-warning fs-4"></i>
                </div>
                <div>
                    <?php $d1 = $package['display_hotel_1'] ?? null; ?>
                    <h6 class="fw-bold text-dark mb-0"><?= $d1 ? esc($d1['name']) : esc($package['hotel_mekkah'] ?? '—') ?></h6>
                    <small class="text-secondary"><?= $d1 && $d1['city'] ? esc($d1['city']) : ('Hotel ' . esc($city1_name ?? 'Kota 1')) ?></small>
                    <div class="text-warning mt-1" style="font-size: 0.7rem;">
                        <?= $d1 ? str_repeat('★', $d1['stars']) : str_repeat('★', (int)($package['hotel_mekkah_stars'] ?? 0)) ?>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center mb-2 p-3 bg-light rounded-4">
                <div class="bg-white rounded-circle p-3 shadow-sm me-3">
                    <i class="bi bi-stars text-warning fs-4"></i>
                </div>
                <div>
                    <?php $d2 = $package['display_hotel_2'] ?? null; ?>
                    <h6 class="fw-bold text-dark mb-0"><?= $d2 ? esc($d2['name']) : esc($package['hotel_madinah'] ?? '—') ?></h6>
                    <small class="text-secondary"><?= $d2 && $d2['city'] ? esc($d2['city']) : ('Hotel ' . esc($city2_name ?? 'Kota 2')) ?></small>
                    <div class="text-warning mt-1" style="font-size: 0.7rem;">
                        <?= $d2 ? str_repeat('★', $d2['stars']) : str_repeat('★', (int)($package['hotel_madinah_stars'] ?? 0)) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="bi bi-airplane-engines-fill me-2 text-primary"></i>Informasi Penerbangan</h5>
            <div class="p-3 bg-light rounded-4">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary small fw-bold text-uppercase">Maskapai</span>
                    <span class="text-dark fw-bold"><?= esc($package['airline']) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-secondary small fw-bold text-uppercase">Rute Penerbangan</span>
                    <span class="text-dark fw-bold"><?= esc($package['flight_route']) ?></span>
                </div>
            </div>
        </div>

        <?php $commission_per_pax = (float)($package['commission_per_pax'] ?? 0); ?>
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mt-4">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-3"><i class="bi bi-cash-stack me-2 text-success"></i>Komisi Agency</h5>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="text-secondary small">Komisi per pack</span>
                <div class="d-flex align-items-center gap-2">
                    <span id="commissionDisplay" class="fw-bold text-dark">Rp ••••••</span>
                    <span id="commissionValue" class="fw-bold text-success d-none">Rp <?= number_format($commission_per_pax, 0, ',', '.') ?></span>
                    <button type="button" id="toggleCommission" class="btn btn-sm btn-light border rounded-pill px-3 py-1" title="Tampilkan / Sembunyikan komisi" aria-label="Toggle komisi">
                        <i class="bi bi-eye" id="commissionIcon"></i>
                        <span id="commissionLabel">Tampilkan</span>
                    </button>
                </div>
            </div>
            <p class="text-muted small mb-0 mt-2">Klik untuk menampilkan atau menyembunyikan nominal komisi.</p>
        </div>
    </div>
</div>

<script>
(function() {
    var display = document.getElementById('commissionDisplay');
    var value = document.getElementById('commissionValue');
    var btn = document.getElementById('toggleCommission');
    var icon = document.getElementById('commissionIcon');
    var label = document.getElementById('commissionLabel');
    var visible = false;
    function toggle() {
        visible = !visible;
        display.classList.toggle('d-none', visible);
        value.classList.toggle('d-none', !visible);
        icon.className = visible ? 'bi bi-eye-slash' : 'bi bi-eye';
        label.textContent = visible ? 'Sembunyikan' : 'Tampilkan';
    }
    if (btn) btn.addEventListener('click', toggle);
})();
</script>
<?= $this->endSection() ?>