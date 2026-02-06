<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <a href="<?= base_url('agency/team') ?>" class="btn btn-link text-decoration-none text-secondary mb-3 ps-0">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Tim
        </a>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="mb-4">
                    <h1 class="h3 fw-bold text-dark mb-1">Tambah Anggota Baru</h1>
                    <p class="text-secondary">Daftarkan anggota baru ke agensi Anda</p>
                </div>
            
                <?php if(isset($validation)):?>
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3 mb-4">
                         <ul class="mb-0 ps-3">
                            <?= $validation->listErrors() ?>
                        </ul>
                    </div>
                <?php endif;?>

                <form action="<?= base_url('agency/store_member') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="cth. Ahmad Fadillah" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="cth. ahmad@example.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" placeholder="cth. 08123456789" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2 fw-bold">
                            <i class="bi bi-person-plus-fill me-2"></i> Daftarkan Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
