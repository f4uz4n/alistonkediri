<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-6">
        <a href="<?= base_url('owner/cities') ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-3"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        <h2 class="fw-800 text-dark mb-1">Tambah Kota</h2>
        <p class="text-secondary mb-4">Nama kota akan dipakai di master hotel dan label paket</p>

        <form action="<?= base_url('owner/cities/store') ?>" method="post" class="card border-0 shadow-sm rounded-4 p-4">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="form-label fw-bold">Nama Kota</label>
                <input type="text" name="name" class="form-control form-control-lg bg-light border-0" required placeholder="Contoh: Mekkah" minlength="2">
            </div>
            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold"><i class="bi bi-check2 me-2"></i>Simpan Kota</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
