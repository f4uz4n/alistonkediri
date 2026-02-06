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
                <p class="text-white text-opacity-75 text-uppercase small fw-bold ls-1 mb-0">Total Penghasilan</p>
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

<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold text-dark mb-3">Materi Pemasaran</h4>
    </div>
</div>

<div class="row g-4">
    <?php if(empty($materials)): ?>
        <div class="col-12 text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-folder2-open text-secondary fs-1"></i>
            </div>
            <h4 class="text-dark">Tidak ada materi tersedia</h4>
            <p class="text-muted">Cek kembali nanti untuk update dari pemilik.</p>
        </div>
    <?php else: ?>
        <?php foreach($materials as $material): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                <!-- Content Preview Area -->
                <div class="watermark-container bg-light d-flex align-items-center justify-content-center" style="height: 250px; position: relative;">
                    <?php if($material['type'] == 'file'): ?>
                        <?php 
                            $ext = strtolower(pathinfo($material['file_path'], PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                        ?>
                        <?php if($isImage): ?>
                            <img src="<?= base_url($material['file_path']) ?>" class="img-fluid h-100 w-100 object-fit-cover" draggable="false">
                        <?php elseif($ext == 'pdf'): ?>
                            <iframe src="<?= base_url($material['file_path']) ?>#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="100%" style="border: none;"></iframe>
                        <?php else: ?>
                            <div class="text-center">
                                <i class="bi bi-file-earmark-fill text-secondary display-4"></i>
                                <p class="small text-muted mb-0"><?= strtoupper($ext) ?> File</p>
                            </div>
                        <?php endif; ?>
                    <?php elseif($material['type'] == 'youtube'): ?>
                        <?php 
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $material['url'], $match);
                            $video_id = $match[1] ?? '';
                        ?>
                        <?php if($video_id): ?>
                            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?= $video_id ?>?modestbranding=1&rel=0" frameborder="0" allowfullscreen></iframe>
                        <?php else: ?>
                            <i class="bi bi-youtube text-danger display-4"></i>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center">
                            <i class="bi bi-link-45deg text-primary display-4"></i>
                            <p class="small text-muted mb-0">External Link</p>
                        </div>
                    <?php endif; ?>

                    <div class="watermark-overlay">
                        <?php 
                            $watermarkText = 'AGENCY: ' . session()->get('username') . ' - TOUR & TRAVEL';
                            for($i=0; $i<6; $i++): 
                        ?>
                            <span class="watermark-text"><?= $watermarkText ?></span>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                         <span class="badge bg-primary bg-opacity-10 text-primary border-0 rounded-pill px-3 py-2 small">
                            <?= ucfirst($material['type']) ?>
                         </span>
                         <small class="ms-auto text-muted"><?= date('d M Y', strtotime($material['created_at'])) ?></small>
                    </div>
                    
                    <h5 class="card-title fw-bold text-dark mb-2"><?= esc($material['title']) ?></h5>
                    <p class="card-text text-secondary mb-4 flex-grow-1 small line-clamp-2">
                        <?= esc($material['description']) ?>
                    </p>
                    
                    <?php if($material['type'] == 'url'): ?>
                        <a href="<?= esc($material['url']) ?>" target="_blank" class="btn btn-primary w-100 fw-bold rounded-pill">
                            <i class="bi bi-box-arrow-up-right me-2"></i> Buka Link
                        </a>
                    <?php elseif($material['type'] == 'file'): ?>
                        <button type="button" class="btn btn-primary w-100 fw-bold rounded-pill" onclick="openSecureModal('<?= $material['type'] ?>', '<?= base_url($material['file_path']) ?>', '<?= esc($material['title']) ?>')">
                            <i class="bi bi-zoom-in me-2"></i> Lihat Materi
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
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
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<!-- Secure Zoom Modal -->
<div class="modal fade" id="secureModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-white py-3 px-4">
                <h5 class="modal-title fw-bold text-dark" id="secureModalTitle">Materi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-light" style="min-height: 500px;">
                <div class="watermark-container h-100 w-100 d-flex align-items-center justify-content-center" oncontextmenu="return false;" style="min-height: 500px; position: relative;">
                    <div id="secureModalContent" class="h-100 w-100 text-center">
                        <!-- Content injected by JS -->
                    </div>
                    
                    <!-- Modal Watermark Overlay -->
                    <div class="watermark-overlay" style="z-index: 120;">
                        <?php 
                            $watermarkText = 'AGENCY: ' . session()->get('username') . ' - SANGAT RAHASIA';
                            for($i=0; $i<12; $i++): 
                        ?>
                            <span class="watermark-text"><?= $watermarkText ?></span>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openSecureModal(type, url, title) {
    const modal = new bootstrap.Modal(document.getElementById('secureModal'));
    const container = document.getElementById('secureModalContent');
    const titleElement = document.getElementById('secureModalTitle');
    
    titleElement.innerText = title;
    container.innerHTML = '';
    
    if (type === 'file') {
        const ext = url.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
            container.innerHTML = `<img src="${url}" class="img-fluid" style="max-height: 80vh; user-select: none;" draggable="false" oncontextmenu="return false;">`;
        } else if (ext === 'pdf') {
            container.innerHTML = `<iframe src="${url}#toolbar=0" width="100%" height="600px" style="border: none;"></iframe>`;
        }
    }
    
    modal.show();
}

document.querySelectorAll('.watermark-container').forEach(el => {
    el.addEventListener('contextmenu', e => e.preventDefault());
});
</script>
<?= $this->endSection() ?>
