<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <a href="<?= base_url('owner') ?>" class="btn btn-link text-decoration-none text-secondary mb-3 ps-0">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="mb-4">
                    <h1 class="h3 fw-bold text-dark mb-1">Upload Materi</h1>
                    <p class="text-secondary">Bagikan materi promosi dengan agensi</p>
                </div>
            
                <?php if(isset($validation)):?>
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3 mb-4">
                         <ul class="mb-0 ps-3">
                            <?= $validation->listErrors() ?>
                        </ul>
                    </div>
                <?php endif;?>

                <form action="<?= base_url('owner/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Tipe Materi</label>
                        <div class="d-flex gap-3 mt-1">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeFile" value="file" checked>
                                <label class="form-check-label" for="typeFile">File Upload</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeYoutube" value="youtube">
                                <label class="form-check-label" for="typeYoutube">YouTube</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeUrl" value="url">
                                <label class="form-check-label" for="typeUrl">File URL</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Judul</label>
                        <input type="text" name="title" class="form-control" placeholder="cth. Promo Musim Panas 2026" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat materi..."></textarea>
                    </div>

                    <div id="fileInputSection" class="mb-4">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Lampiran File</label>
                        <div class="input-group">
                            <input type="file" name="attachment" class="form-control" id="inputGroupFile02" accept=".pdf,.jpg,.jpeg,.png">
                            <label class="input-group-text bg-light text-secondary" for="inputGroupFile02">Upload</label>
                        </div>
                        <div class="form-text text-muted small mt-2">Tipe diperbolehkan: PDF, JPG, PNG. Ukuran maks: 4MB</div>
                    </div>

                    <div id="urlInputSection" class="mb-4" style="display: none;">
                        <label class="form-label fw-bold text-secondary small text-uppercase">URL (YouTube / Website)</label>
                        <input type="url" name="url" class="form-control" placeholder="https://youtube.com/watch?v=...">
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary py-3 fw-bold">
                            <i class="bi bi-cloud-upload-fill me-2"></i> Simpan Materi
                        </button>
                    </div>
                </form>

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

    toggleSections(); // Initial state
});
</script>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
