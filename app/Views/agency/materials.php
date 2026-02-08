<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12">
        <h2 class="fw-800 text-dark mb-1">Materi Promosi</h2>
        <p class="text-secondary mb-0">Materi pemasaran dari kantor pusat untuk Anda gunakan</p>
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
                <div class="material-preview bg-light d-flex align-items-center justify-content-center" style="height: 250px; position: relative;" oncontextmenu="return false;">
                    <?php if($material['type'] == 'file'): ?>
                        <?php 
                            $ext = strtolower(pathinfo($material['file_path'], PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                        ?>
                        <?php if($isImage): ?>
                            <img src="<?= base_url($material['file_path']) ?>" class="img-fluid h-100 w-100 object-fit-cover material-no-download" draggable="false">
                        <?php elseif($ext == 'pdf'): ?>
                            <iframe src="<?= base_url($material['file_path']) ?>#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="100%" style="border: none; pointer-events: auto;" title="Preview PDF"></iframe>
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
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.material-no-download, .material-preview img {
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    pointer-events: none;
}
.material-preview iframe {
    pointer-events: auto;
}
</style>

<div class="modal fade" id="secureModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-white py-3 px-4">
                <h5 class="modal-title fw-bold text-dark" id="secureModalTitle">Materi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-light no-right-click" style="min-height: 500px;" oncontextmenu="return false;">
                <div class="material-preview h-100 w-100 d-flex align-items-center justify-content-center" oncontextmenu="return false;" style="min-height: 500px; position: relative;">
                    <div id="secureModalContent" class="h-100 w-100 text-center" oncontextmenu="return false;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openSecureModal(type, url, title) {
    var modal = new bootstrap.Modal(document.getElementById('secureModal'));
    var container = document.getElementById('secureModalContent');
    var titleElement = document.getElementById('secureModalTitle');
    
    titleElement.innerText = title;
    container.innerHTML = '';
    
    if (type === 'file') {
        var ext = url.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
            container.innerHTML = '<img src="' + url + '" class="img-fluid material-no-download" style="max-height: 80vh;" draggable="false" oncontextmenu="return false;">';
        } else if (ext === 'pdf') {
            container.innerHTML = '<iframe src="' + url + '#toolbar=0&navpanes=0" width="100%" height="600px" style="border: none;" title="Lihat PDF" oncontextmenu="return false;"></iframe>';
        }
    }
    
    modal.show();
}

function blockRightClick(e) {
    if (document.getElementById('secureModal').classList.contains('show')) {
        var target = e.target;
        var modal = document.getElementById('secureModal');
        if (modal.contains(target)) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    }
}
document.addEventListener('contextmenu', blockRightClick, true);

document.querySelectorAll('.material-preview').forEach(function(el) {
    el.addEventListener('contextmenu', function(e) { e.preventDefault(); return false; }, true);
});
</script>
<?= $this->endSection() ?>
