<?php
helper('branding');
$agencies = $agencies ?? [];
$this->extend('layouts/public');
?>
<?= $this->section('content') ?>

<section class="py-5 bg-light">
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active fw-bold" aria-current="page">Agen Mitra</li>
            </ol>
        </nav>
        <div class="text-center mb-5">
            <h1 class="section-title display-6 mb-2">Daftar Agen Mitra</h1>
            <p class="text-secondary">Hubungi agen terdekat untuk pendaftaran paket. Tunjukkan foto, nama, dan alamat di bawah.</p>
        </div>
        <?php if (empty($agencies)): ?>
        <div class="text-center py-5">
            <i class="bi bi-people text-muted display-4"></i>
            <p class="text-secondary mt-3">Belum ada agen mitra terdaftar. Hubungi admin untuk informasi.</p>
            <a href="<?= base_url() ?>#kontak" class="btn btn-primary rounded-pill mt-3">Hubungi Kami</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($agencies as $a):
                $imgPath = !empty($a['company_logo']) ? $a['company_logo'] : ($a['profile_pic'] ?? '');
                $foto = $imgPath ? base_url($imgPath) : '';
                $nama = !empty($a['company_name']) ? $a['company_name'] : ($a['full_name'] ?? $a['username'] ?? 'Agen');
                $alamat = $a['address'] ?? '-';
                $telp = $a['phone'] ?? '';
                $wa = $telp ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $telp) : '';
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-agency h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <?php if ($foto): ?>
                                <img src="<?= $foto ?>" alt="<?= esc($nama) ?>" class="agency-avatar">
                            <?php else: ?>
                                <div class="agency-avatar d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 1.5rem;"><?= strtoupper(substr($nama, 0, 1)) ?></div>
                            <?php endif; ?>
                        </div>
                        <h6 class="fw-bold text-dark mb-1"><?= esc($nama) ?></h6>
                        <p class="small text-secondary mb-2"><i class="bi bi-geo-alt me-1"></i><?= esc($alamat) ?></p>
                        <?php if ($wa): ?>
                        <a href="<?= esc($wa) ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm rounded-pill"><i class="bi bi-whatsapp me-1"></i> Hubungi</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= base_url() ?>#agensi" class="btn btn-outline-primary rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
