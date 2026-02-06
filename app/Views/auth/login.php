<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5 col-lg-4">
        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                <i class="bi bi-airplane-engines-fill fs-3"></i>
            </div>
            <h2 class="fw-bold text-dark">Selamat Datang</h2>
            <p class="text-secondary">Silakan masuk untuk melanjutkan</p>
        </div>

        <div class="card border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.9);">
            <div class="card-body p-4 p-md-5">
                <?php if(isset($validation)):?>
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3 mb-4">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <?= $validation->listErrors() ?>
                    </div>
                <?php endif;?>

                <form action="<?= base_url('auth/login') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold text-uppercase ls-1">Nama Pengguna</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control bg-light border-start-0 ps-0" placeholder="Masukkan nama pengguna" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold text-uppercase ls-1">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0 ps-0" placeholder="Masukkan kata sandi" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm mb-3">
                        Masuk <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
