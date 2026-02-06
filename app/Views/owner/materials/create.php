<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-800 text-dark mb-1">Tambah Materi Promosi</h2>
                <p class="text-secondary mb-0">Bagikan aset pemasaran baru dengan agensi mitra</p>
            </div>
            <a href="<?= base_url('owner/materials') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-body p-4 p-md-5">
                <form action="<?= base_url('owner/materials/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Tipe Konten</label>
                        <div class="row g-3">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="type" id="typeFile" value="file" checked>
                                <label class="btn btn-outline-primary rounded-4 w-100 py-3 fw-bold border-2" for="typeFile">
                                    <i class="bi bi-file-earmark-arrow-up d-block fs-4 mb-2"></i> File Upload
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="type" id="typeYoutube" value="youtube">
                                <label class="btn btn-outline-primary rounded-4 w-100 py-3 fw-bold border-2" for="typeYoutube">
                                    <i class="bi bi-youtube d-block fs-4 mb-2"></i> YouTube
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="type" id="typeUrl" value="url">
                                <label class="btn btn-outline-primary rounded-4 w-100 py-3 fw-bold border-2" for="typeUrl">
                                    <i class="bi bi-link-45deg d-block fs-4 mb-2"></i> Website URL
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Judul Materi</label>
                        <input type="text" name="title" class="form-control form-control-lg bg-light border-0" placeholder="cth: Poster Promo Akhir Tahun" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Deskripsi Singkat (Optional)</label>
                        <textarea name="description" class="form-control bg-light border-0" rows="3" placeholder="Jelaskan sedikit tentang materi ini..."></textarea>
                    </div>

                    <div id="fileInputSection" class="mb-5">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Pilih File Lampiran</label>
                        <div class="p-4 border-2 border-dashed rounded-4 text-center bg-light">
                            <i class="bi bi-cloud-arrow-up fs-1 text-primary opacity-50 d-block mb-2"></i>
                            <input type="file" name="attachment" class="form-control bg-white border" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted d-block mt-2 font-outfit small">Format: PDF, JPG, PNG. Ukuran Maks: 4MB</small>
                        </div>
                    </div>

                    <div id="urlInputSection" class="mb-5" style="display: none;">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Tautan / Link Materi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-link"></i></span>
                            <input type="url" name="url" class="form-control form-control-lg bg-light border-0" placeholder="https://youtube.com/watch?v=...">
                        </div>
                    </div>

                    <button type="submit" class="btn-premium w-100 rounded-pill py-3 shadow-lg">
                        <i class="bi bi-cloud-upload-fill me-2"></i>Simpan & Publikasikan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="type"]');
    const fileSection = document.getElementById('fileInputSection');
    const urlSection = document.getElementById('urlInputSection');
    const fileInput = fileSection.querySelector('input[type="file"]');
    const urlInput = urlSection.querySelector('input[type="url"]');

    function toggleSections() {
        const selectedValue = document.querySelector('input[name="type"]:checked').value;
        if (selectedValue === 'file') {
            fileSection.style.display = 'block';
            urlSection.style.display = 'none';
            fileInput.required = true;
            urlInput.required = false;
        } else {
            fileSection.style.display = 'none';
            urlSection.style.display = 'block';
            fileInput.required = false;
            urlInput.required = true;
        }
    }

    radioButtons.forEach(radio => {
        radio.addEventListener('change', toggleSections);
    });

    toggleSections();
});
</script>
<?= $this->endSection() ?>