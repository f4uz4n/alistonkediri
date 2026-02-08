<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $m = $material ?? []; ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-800 text-dark mb-1">Edit Materi Promosi</h2>
                <p class="text-secondary mb-0">Ubah data materi</p>
            </div>
            <a href="<?= base_url('owner/materials') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <?php if (session()->getFlashdata('errors')): $errs = session()->getFlashdata('errors'); ?>
        <div class="alert alert-danger border-0 rounded-4 mb-3">
            <ul class="mb-0 list-unstyled"><?php foreach ($errs as $e): ?><li><?= is_array($e) ? implode(' ', $e) : esc($e) ?></li><?php endforeach; ?></ul>
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-body p-4 p-md-5">
                <form action="<?= base_url('owner/materials/update/' . $m['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Tipe Konten</label>
                        <div class="row g-3">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="type" id="typeFile" value="file" <?= ($m['type'] ?? '') === 'file' ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary rounded-4 w-100 py-3 fw-bold border-2" for="typeFile">
                                    <i class="bi bi-file-earmark-arrow-up d-block fs-4 mb-2"></i> File Upload
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="type" id="typeYoutube" value="youtube" <?= ($m['type'] ?? '') === 'youtube' ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary rounded-4 w-100 py-3 fw-bold border-2" for="typeYoutube">
                                    <i class="bi bi-youtube d-block fs-4 mb-2"></i> YouTube
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="type" id="typeUrl" value="url" <?= ($m['type'] ?? '') === 'url' ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary rounded-4 w-100 py-3 fw-bold border-2" for="typeUrl">
                                    <i class="bi bi-link-45deg d-block fs-4 mb-2"></i> Website URL
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Judul Materi</label>
                        <input type="text" name="title" class="form-control form-control-lg bg-light border-0" value="<?= esc($m['title'] ?? '') ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Deskripsi Singkat (Optional)</label>
                        <textarea name="description" class="form-control bg-light border-0" rows="3"><?= esc($m['description'] ?? '') ?></textarea>
                    </div>

                    <div id="fileInputSection" class="mb-5">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">File Lampiran</label>
                        <?php if (!empty($m['file_path'])): ?>
                        <p class="small text-secondary mb-2">File saat ini: <a href="<?= base_url($m['file_path']) ?>" target="_blank"><?= esc(basename($m['file_path'])) ?></a></p>
                        <?php endif; ?>
                        <div class="p-4 border-2 border-dashed rounded-4 text-center bg-light">
                            <i class="bi bi-cloud-arrow-up fs-1 text-primary opacity-50 d-block mb-2"></i>
                            <input type="file" name="attachment" class="form-control bg-white border" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted d-block mt-2 font-outfit small">Kosongkan jika tidak ingin mengganti. Format: PDF, JPG, PNG. Maks: 4MB</small>
                        </div>
                    </div>

                    <div id="urlInputSection" class="mb-5" style="display: none;">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Tautan / Link Materi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-link"></i></span>
                            <input type="url" name="url" class="form-control form-control-lg bg-light border-0" value="<?= esc($m['url'] ?? '') ?>" placeholder="https://...">
                        </div>
                    </div>

                    <button type="submit" class="btn-premium w-100 rounded-pill py-3 shadow-lg">
                        <i class="bi bi-check2-circle me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileSection = document.getElementById('fileInputSection');
    const urlSection = document.getElementById('urlInputSection');
    const fileInput = fileSection.querySelector('input[type="file"]');
    const urlInput = urlSection.querySelector('input[type="url"]');

    function toggleSections() {
        const selected = document.querySelector('input[name="type"]:checked');
        if (!selected) return;
        if (selected.value === 'file') {
            fileSection.style.display = 'block';
            urlSection.style.display = 'none';
            urlInput.required = false;
        } else {
            fileSection.style.display = 'none';
            urlSection.style.display = 'block';
            urlInput.required = true;
            fileInput.required = false;
        }
    }
    document.querySelectorAll('input[name="type"]').forEach(function(r) { r.addEventListener('change', toggleSections); });
    toggleSections();
});
</script>
<?= $this->endSection() ?>
