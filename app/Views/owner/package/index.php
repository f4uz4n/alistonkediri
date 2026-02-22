<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php helper('package'); ?>
<?php if (session()->getFlashdata('msg')): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
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
        <?php foreach($packages as $package): 
            $depDate = isset($package['departure_date']) ? substr((string)$package['departure_date'], 0, 10) : '';
            $isExpired = ($depDate !== '' && $depDate < date('Y-m-d'));
        ?>
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden bg-white <?= empty($package['is_active']) && !$isExpired ? 'opacity-90' : '' ?>">
                <div class="position-relative">
                    <?php if ($package['image']): ?>
                        <a href="<?= base_url($package['image']) ?>" target="_blank" rel="noopener noreferrer" class="d-block text-decoration-none" title="Preview gambar">
                            <div style="height: 200px; background: url('<?= base_url($package['image']) ?>') center/cover no-repeat;" class="package-card-img"></div>
                        </a>
                    <?php else: ?>
                        <div style="height: 100px; background: linear-gradient(135deg, var(--primary-color) 0%, #ff7675 100%);"></div>
                    <?php endif; ?>
                    <?php if ($isExpired): ?>
                        <span class="position-absolute top-0 end-0 m-2 badge bg-dark rounded-pill px-3 py-2 shadow-sm">Expired</span>
                    <?php elseif (empty($package['is_active'])): ?>
                        <span class="position-absolute top-0 end-0 m-2 badge bg-secondary rounded-pill px-3 py-2 shadow-sm">Kuota Penuh</span>
                    <?php endif; ?>
                    <div class="position-absolute top-100 start-0 translate-middle-y ps-4 w-100 d-flex justify-content-between pe-4">
                        <div class="bg-white rounded-circle shadow-sm p-3 border d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <img src="<?= get_company_logo() ?>" alt="Logo" style="width: 35px;">
                        </div>
                        <div class="bg-white rounded-4 shadow-sm px-4 py-2 border d-flex flex-column align-items-center justify-content-center">
                            <h3 class="fw-800 text-primary mb-0"><?= format_price_display($package['price']) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 pt-5 mt-2">
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1"><i class="bi bi-calendar-check me-1"></i> Keberangkatan</span>
                            <span class="text-dark fw-bold"><?= date('d M Y', strtotime($package['departure_date'])) ?></span>
                        </div>
                        <div class="text-end">
                            <div class="bg-primary text-white rounded-4 px-3 py-2 shadow-sm d-inline-block">
                                <span class="d-block small text-white-50 fw-bold text-uppercase ls-1" style="font-size: 0.65rem;">Durasi</span>
                                <h4 class="mb-0 fw-800"><?= esc($package['duration']) ?></h4>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="fw-800 text-dark mb-4 ls-n1"><?= esc($package['name']) ?></h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="bg-light rounded-4 p-3 border">
                                <small class="text-secondary d-block mb-1 fw-bold text-uppercase" style="font-size: 0.6rem;">Hotel <?= esc($city1_name ?? 'Kota 1') ?></small>
                                <?php $h1 = $package['display_hotel_1'] ?? null; ?>
                                <span class="d-block fw-bold text-dark small text-truncate"><?= $h1 ? esc($h1['name']) : esc($package['hotel_mekkah'] ?? '—') ?></span>
                                <?php if ($h1 && $h1['city']): ?><small class="text-muted d-block"><?= esc($h1['city']) ?></small><?php endif; ?>
                                <div class="text-warning" style="font-size: 0.7rem;">
                                    <?= $h1 ? str_repeat('★', $h1['stars']) : str_repeat('★', (int)($package['hotel_mekkah_stars'] ?? 0)) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-4 p-3 border">
                                <small class="text-secondary d-block mb-1 fw-bold text-uppercase" style="font-size: 0.6rem;">Hotel <?= esc($city2_name ?? 'Kota 2') ?></small>
                                <?php $h2 = $package['display_hotel_2'] ?? null; ?>
                                <span class="d-block fw-bold text-dark small text-truncate"><?= $h2 ? esc($h2['name']) : esc($package['hotel_madinah'] ?? '—') ?></span>
                                <?php if ($h2 && $h2['city']): ?><small class="text-muted d-block"><?= esc($h2['city']) ?></small><?php endif; ?>
                                <div class="text-warning" style="font-size: 0.7rem;">
                                    <?= $h2 ? str_repeat('★', $h2['stars']) : str_repeat('★', (int)($package['hotel_madinah_stars'] ?? 0)) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark small text-uppercase mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Sudah Termasuk:</h6>
                            <ul class="list-unstyled mb-0" style="font-size: 0.8rem;">
                                <?php 
                                    $incs = json_decode($package['inclusions'], true);
                                    if(is_array($incs)): 
                                        foreach(array_slice($incs, 0, 5) as $inc): 
                                ?>
                                    <li class="text-secondary mb-1">√ <?= esc($inc) ?></li>
                                <?php 
                                        endforeach; 
                                        if(count($incs) > 5) echo '<li class="text-primary fw-bold mt-1">+'.(count($incs)-5).' lainnya...</li>';
                                    endif; 
                                ?>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark small text-uppercase mb-2"><i class="bi bi-gift-fill text-warning me-2"></i>Gratis / Free:</h6>
                            <ul class="list-unstyled mb-0" style="font-size: 0.8rem;">
                                <?php 
                                    $frees = json_decode($package['freebies'], true);
                                    if(is_array($frees)): 
                                        foreach(array_slice($frees, 0, 5) as $free): 
                                ?>
                                    <li class="text-secondary mb-1">• <?= esc($free) ?></li>
                                <?php 
                                        endforeach;
                                    endif; 
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php 
                        $excls = json_decode($package['exclusions'] ?? '[]', true);
                        if (is_array($excls) && count($excls) > 0): 
                    ?>
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold text-dark small text-uppercase mb-2"><i class="bi bi-x-circle text-secondary me-2"></i>Belum Termasuk:</h6>
                            <ul class="list-unstyled mb-0" style="font-size: 0.8rem;">
                                <?php foreach (array_slice($excls, 0, 5) as $ex): ?>
                                    <li class="text-secondary mb-1">× <?= esc($ex) ?></li>
                                <?php endforeach; ?>
                                <?php if (count($excls) > 5): ?><li class="text-primary fw-bold mt-1">+<?= count($excls) - 5 ?> lainnya...</li><?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!$isExpired): ?>
                    <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                        <a href="<?= base_url('package/edit/'.$package['id']) ?>" class="btn btn-light rounded-pill px-4 fw-bold border">
                            <i class="bi bi-pencil-square me-2"></i>Edit
                        </a>
                        <form action="<?= base_url('package/toggle-status/'.$package['id']) ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <?php if (!empty($package['is_active'])): ?>
                                <button type="submit" class="btn btn-outline-warning rounded-pill px-3 fw-bold border py-2" title="Tandai kuota penuh">
                                    <i class="bi bi-person-x me-1"></i>Tandai Kuota Penuh
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-outline-success rounded-pill px-3 fw-bold border py-2" title="Buka kembali pendaftaran">
                                    <i class="bi bi-person-check me-1"></i>Buka Kembali
                                </button>
                            <?php endif; ?>
                        </form>
                        <button type="button" class="btn btn-light text-danger rounded-pill flex-grow-1 border py-2 fw-bold btn-delete-package"
                           data-bs-toggle="modal" data-bs-target="#modalHapusPaket"
                           data-package-id="<?= (int)$package['id'] ?>"
                           data-package-name="<?= esc($package['name']) ?>"
                           data-delete-url="<?= base_url('package/delete/'.$package['id']) ?>">
                            <i class="bi bi-trash me-1"></i>Hapus Paket
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="pt-3 border-top">
                        <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i>Tanggal keberangkatan telah lewat. Edit dan hapus tidak tersedia.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Konfirmasi Hapus Paket -->
<div class="modal fade" id="modalHapusPaket" tabindex="-1" aria-labelledby="modalHapusPaketLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-body text-center p-5">
                <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="fw-800 text-dark mb-2" id="modalHapusPaketLabel">Hapus Paket?</h5>
                <p class="text-secondary small mb-3" id="modalHapusPaketNama"></p>
                <p class="text-muted small mb-4">
                    Jika paket dihapus, <strong>semua data terkait</strong> (pendaftaran jamaah, pembayaran, dokumen, dll.) akan terpengaruh. Tindakan ini <strong>tidak dapat dibatalkan</strong>.
                </p>
                <form id="formHapusPaket" method="post" action="" class="d-flex gap-2 justify-content-center flex-wrap">
                    <?= csrf_field() ?>
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                        <i class="bi bi-trash me-1"></i>Ya, Hapus Paket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var modal = document.getElementById('modalHapusPaket');
    if (!modal) return;
    var formHapus = document.getElementById('formHapusPaket');
    var labelNama = document.getElementById('modalHapusPaketNama');

    modal.addEventListener('show.bs.modal', function(e) {
        var btn = e.relatedTarget;
        if (btn && btn.classList.contains('btn-delete-package')) {
            var nama = btn.getAttribute('data-package-name') || 'Paket ini';
            var url = btn.getAttribute('data-delete-url') || '';
            labelNama.textContent = nama;
            if (formHapus) formHapus.action = url;
        }
    });
})();
</script>
<?= $this->endSection() ?>