<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= base_url('agency') ?>" class="text-decoration-none text-secondary">Dashboard</a></li>
                <li class="breadcrumb-item active fw-bold" aria-current="page">Ubah Profil</li>
            </ol>
        </nav>
        <h2 class="fw-800 text-dark mb-1">Ubah Profil</h2>
        <p class="text-secondary mb-0">Kelola nama lengkap, foto, telepon, alamat, dan password akun agency</p>
    </div>
</div>

<?php if (session()->getFlashdata('msg')): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('agency/update-profile') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <h6 class="text-secondary small fw-bold text-uppercase ls-1 mb-4 text-start">Foto Profil</h6>
                    <div class="mb-3">
                        <?php if (!empty($user['profile_pic'])): ?>
                            <img src="<?= base_url($user['profile_pic']) ?>" alt="Profil" class="rounded-circle border border-4 border-primary shadow-sm" style="width: 120px; height: 120px; object-fit: cover;" id="profilePreview">
                        <?php else: ?>
                            <div class="rounded-circle bg-light text-secondary d-inline-flex align-items-center justify-content-center border border-4 border-white shadow-sm" style="width: 120px; height: 120px;" id="profilePreview">
                                <i class="bi bi-person-fill fs-1"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="profile_pic" class="form-control form-control-sm bg-light border-0 rounded-pill" accept="image/*" id="profilePicInput">
                    <small class="text-muted d-block mt-2">Format: JPG, PNG. Maks: 2MB</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 py-4 px-4 border-bottom">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Informasi Profil</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control form-control-lg bg-light border-0 rounded-3" value="<?= esc($user['full_name'] ?? '') ?>" required placeholder="Nama lengkap agency">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" class="form-control form-control-lg bg-light border-0 rounded-3" value="<?= esc($user['phone'] ?? '') ?>" required placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0 rounded-3" value="<?= esc($user['email'] ?? '') ?>" placeholder="email@contoh.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Alamat</label>
                            <textarea name="address" class="form-control bg-light border-0 rounded-3" rows="3" placeholder="Alamat lengkap"><?= esc($user['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-header bg-transparent border-0 py-4 px-4 border-bottom">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock-fill me-2 text-warning"></i>Keamanan</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Ganti Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-key text-secondary"></i></span>
                                <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-end" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password">
                            </div>
                            <small class="text-muted">Minimal 6 karakter. Isi hanya jika ingin mengubah password.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="<?= base_url('agency') ?>" class="btn btn-light border rounded-pill px-4 fw-bold me-2">Batal</a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">
                    <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.getElementById('profilePicInput').addEventListener('change', function(e) {
    var file = e.target.files[0];
    var preview = document.getElementById('profilePreview');
    if (!file || !file.type.match('image.*')) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        if (preview.tagName === 'IMG') {
            preview.src = e.target.result;
        } else {
            var img = document.createElement('img');
            img.id = 'profilePreview';
            img.className = 'rounded-circle border border-4 border-primary shadow-sm';
            img.style.cssText = 'width: 120px; height: 120px; object-fit: cover;';
            img.src = e.target.result;
            img.alt = 'Profil';
            preview.parentNode.replaceChild(img, preview);
        }
    };
    reader.readAsDataURL(file);
});
</script>
<?= $this->endSection() ?>
