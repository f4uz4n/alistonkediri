<?php

$files = [
    'app/Views/owner/package/index.php' => <<<'EOD'
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Paket Perjalanan</h2>
        <p class="text-secondary">Kelola brosur promosi digital Aliston Anda</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <a href="<?= base_url('package/create') ?>" class="btn-premium d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Tambah Paket Baru
        </a>
    </div>
</div>

<div class="row g-4">
    <?php if(empty($packages)): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm py-5 text-center bg-white rounded-4">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="opacity-25 mx-auto text-primary">
                            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM11 7H13V13H11V7ZM11 15H13V17H11V15Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <h4 class="fw-800 text-dark">Belum ada paket perjalanan</h4>
                    <p class="text-secondary mb-4 mx-auto" style="max-width: 400px;">
                        Mulai langkah dakwah Anda dengan membuat paket umroh atau haji yang menarik bagi calon jamaah.
                    </p>
                    <a href="<?= base_url('package/create') ?>" class="btn-premium rounded-pill px-5">
                        <i class="bi bi-magic me-2"></i> Buat Paket Pertama
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach($packages as $package): ?>
        <div class="col-12 col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden bg-white">
                <div class="position-relative">
                    <div style="height: 120px; background: linear-gradient(135deg, var(--primary-color) 0%, #ff7675 100%);"></div>
                    <div class="position-absolute top-100 start-0 translate-middle-y ps-4 w-100 d-flex justify-content-between pe-4">
                        <div class="bg-white rounded-circle shadow-sm p-3 border d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <img src="<?= base_url('assets/img/logo_.png') ?>" alt="Logo" style="width: 40px; filter: grayscale(1);">
                        </div>
                        <div class="bg-white rounded-4 shadow-sm px-4 py-2 border d-flex flex-column align-items-center justify-content-center">
                            <span class="small fw-bold text-secondary text-uppercase ls-1">Mulai</span>
                            <h4 class="fw-800 text-primary mb-0"><?= esc($package['price']) ?><small class="fs-6 ms-1"><?= esc($package['price_unit']) ?></small></h4>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 pt-5 mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-secondary-soft text-secondary-color rounded-pill px-3 py-2 fw-bold small">
                            <i class="bi bi-clock-history me-1"></i> <?= esc($package['duration']) ?>
                        </span>
                        <span class="text-muted small fw-bold text-uppercase"><i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= esc($package['location_start_end']) ?></span>
                    </div>
                    
                    <h5 class="fw-800 text-dark mb-4 ls-n1"><?= esc($package['name']) ?></h5>
                    
                    <div class="bg-light rounded-4 p-4 mb-4">
                        <div class="row g-0">
                            <div class="col-6 border-end pe-3">
                                <small class="text-secondary d-block mb-1 fw-bold text-uppercase" style="font-size: 0.65rem;">Mekkah Hotel</small>
                                <span class="d-block fw-bold text-dark small text-truncate"><?= esc($package['hotel_mekkah']) ?></span>
                                <div class="text-warning" style="font-size: 0.75rem;">
                                    <?= str_repeat('★', $package['hotel_mekkah_stars']) ?>
                                </div>
                            </div>
                            <div class="col-6 ps-3">
                                <small class="text-secondary d-block mb-1 fw-bold text-uppercase" style="font-size: 0.65rem;">Madinah Hotel</small>
                                <span class="d-block fw-bold text-dark small text-truncate"><?= esc($package['hotel_madinah']) ?></span>
                                <div class="text-warning" style="font-size: 0.75rem;">
                                    <?= str_repeat('★', $package['hotel_madinah_stars']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="<?= base_url('package/delete/'.$package['id']) ?>" 
                           class="btn btn-light text-danger rounded-pill flex-grow-1 border py-2 fw-bold"
                           onclick="return confirm('Hapus paket perjalanan ini?')">
                            <i class="bi bi-trash me-2"></i>Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
EOD    ,
    'app/Views/owner/package/create.php' => <<<'EOD'
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-800 text-dark mb-1">Buat Paket Perjalanan</h2>
                <p class="text-secondary mb-0">Lengkapi detail paket untuk dipromosikan oleh agensi Anda</p>
            </div>
            <a href="<?= base_url('package') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <form action="<?= base_url('package/store') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Section 1: Informasi Dasar -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-soft text-primary rounded-circle p-2 me-3">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Informasi Umum</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Nama Paket Perjalanan</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: UMROH SYAWAL 13 HARI" required>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Durasi Paket</label>
                            <input type="text" name="duration" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: 13 Hari" required>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Tanggal Berangkat</label>
                            <input type="date" name="departure_date" class="form-control form-control-lg bg-light border-0" required>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Lokasi Start - End</label>
                            <input type="text" name="location_start_end" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: KEDIRI" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Akomodasi -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-success-soft text-secondary-color rounded-circle p-2 me-3">
                            <i class="bi bi-building-fill fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Penginapan & Akomodasi</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Hotel Mekkah</label>
                            <input type="text" name="hotel_mekkah" class="form-control bg-light border-0 mb-2" placeholder="Nama Hotel" required>
                            <select name="hotel_mekkah_stars" class="form-select bg-light border-0">
                                <option value="3">3 Bintang</option>
                                <option value="4" selected>4 Bintang</option>
                                <option value="5">5 Bintang</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Hotel Madinah</label>
                            <input type="text" name="hotel_madinah" class="form-control bg-light border-0 mb-2" placeholder="Nama Hotel" required>
                            <select name="hotel_madinah_stars" class="form-select bg-light border-0">
                                <option value="3">3 Bintang</option>
                                <option value="4" selected>4 Bintang</option>
                                <option value="5">5 Bintang</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Transportasi & Harga -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-info-soft text-info rounded-circle p-2 me-3">
                            <i class="bi bi-airplane-engines-fill fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Transportasi & Harga</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Maskapai</label>
                            <input type="text" name="airline" class="form-control bg-light border-0" placeholder="Lion Air" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Rute</label>
                            <input type="text" name="flight_route" class="form-control bg-light border-0" placeholder="SUB - JED" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Harga (Nominal)</label>
                            <input type="number" step="0.01" name="price" class="form-control bg-light border-0" placeholder="31.9" required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Unit</label>
                            <input type="text" name="price_unit" class="form-control bg-light border-0" value="JT" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end mb-5">
                <button type="submit" class="btn-premium rounded-pill px-5">
                    <i class="bi bi-check2-circle me-2"></i>Simpan Paket
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
EOD
];

foreach ($files as $path => $content) {
    if (file_exists($path)) {
        unlink($path);
    }
    file_put_contents($path, $content);
    echo "Wrote " . strlen($content) . " bytes to $path\n";
}
