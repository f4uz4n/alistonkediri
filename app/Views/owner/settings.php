<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-4">
    <div class="col-12">
        <h2 class="fw-800 text-dark mb-1">Pengaturan Akun</h2>
        <p class="text-secondary mb-0">Kelola informasi profil, keamanan, dan branding perusahaan Anda</p>
    </div>
</div>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<form action="<?= base_url('owner/update-settings') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Baris 1: Foto Profil & Logo (sejajar) -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <h6 class="text-secondary small fw-bold text-uppercase ls-1 mb-3">Foto Profil</h6>
                    <?php if($user['profile_pic']): ?>
                        <img src="<?= base_url($user['profile_pic']) ?>" alt="Profile" class="rounded-circle border border-3 border-primary-soft shadow-sm mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary-soft text-primary d-inline-flex align-items-center justify-content-center border border-3 border-white shadow-sm mb-3" style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill fs-2"></i>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="profile_pic" class="form-control form-control-sm bg-light border-0 rounded-pill" accept="image/*">
                    <small class="text-muted d-block mt-2 small">JPG, PNG. Maks 2MB</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <h6 class="text-secondary small fw-bold text-uppercase ls-1 mb-3">Logo Perusahaan</h6>
                    <?php if($user['company_logo']): ?>
                        <div class="p-2 border rounded-3 bg-light d-inline-block mb-3">
                            <img src="<?= base_url($user['company_logo']) ?>" alt="Logo" style="max-height: 70px; max-width: 100%;">
                        </div>
                    <?php else: ?>
                        <div class="p-3 border border-2 border-dashed rounded-3 bg-light mb-3">
                            <i class="bi bi-image text-muted fs-4 opacity-50"></i>
                            <p class="small text-secondary mb-0 mt-1">Belum ada logo</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="company_logo" class="form-control form-control-sm bg-light border-0 rounded-pill" accept="image/*">
                    <small class="text-muted d-block mt-2 small">Laporan, struk & kwitansi</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Perusahaan & Perijinan (satu kartu, grid 2 kolom) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-transparent border-0 py-3 px-4 border-bottom">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-building me-2 text-primary"></i>Data Perusahaan & Perijinan</h5>
        </div>
        <div class="card-body p-4 p-md-5">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Nama PT / Perusahaan</label>
                    <input type="text" name="company_name" class="form-control bg-light border-0 rounded-3" value="<?= esc($user['company_name'] ?? '') ?>" placeholder="Contoh: PT Aliston Tour & Travel">
                    <small class="text-muted small">Muncul di kop kwitansi & slip komisi</small>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Slogan</label>
                    <input type="text" name="slogan" class="form-control bg-light border-0 rounded-3" value="<?= esc($user['slogan'] ?? '') ?>" placeholder="Contoh: Perjalanan Aman & Nyaman">
                    <small class="text-muted small">Tagline perusahaan (opsional)</small>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Nama Sekretaris/Bendahara</label>
                    <input type="text" name="nama_sekretaris_bendahara" class="form-control bg-light border-0 rounded-3" value="<?= esc($user['nama_sekretaris_bendahara'] ?? '') ?>" placeholder="Contoh: Siti Aminah, S.E.">
                    <small class="text-muted small">Ditampilkan di halaman verifikasi pembayaran</small>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Nomor SK Perijinan</label>
                    <input type="text" name="no_sk_perijinan" class="form-control bg-light border-0 rounded-3" value="<?= esc($user['no_sk_perijinan'] ?? '') ?>" placeholder="Contoh: 123/DPPT/2024">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Tanggal SK Perijinan</label>
                    <input type="text" name="tanggal_sk_perijinan" class="form-control bg-light border-0 rounded-3" value="<?= esc($user['tanggal_sk_perijinan'] ?? '') ?>" placeholder="Contoh: 15 Januari 2024">
                    <small class="text-muted small">Surat keterangan perijinan (opsional)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Pribadi -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-transparent border-0 py-3 px-4 border-bottom">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Informasi Pribadi</h5>
        </div>
        <div class="card-body p-4 p-md-5">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Nama Lengkap (Direktur)</label>
                    <input type="text" name="full_name" class="form-control bg-light border-0 rounded-3" value="<?= esc($user['full_name']) ?>" required>
                    <small class="text-muted small">Nama direktur di kwitansi</small>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Nomor HP / WhatsApp</label>
                    <input type="text" name="phone" class="form-control bg-light border-0 rounded-3" value="<?= esc($user['phone']) ?>" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Email</label>
                    <input type="email" name="email" class="form-control bg-light border-0 rounded-3" value="<?= esc($user['email']) ?>">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Alamat Lengkap</label>
                    <textarea name="address" class="form-control bg-light border-0 rounded-3" rows="2"><?= esc($user['address']) ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Keamanan Akun -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-transparent border-0 py-3 px-4 border-bottom">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock-fill me-2 text-warning"></i>Keamanan Akun</h5>
        </div>
        <div class="card-body p-4 p-md-5">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-at text-secondary"></i></span>
                        <input type="text" name="username" class="form-control bg-light border-0 rounded-end" value="<?= esc($user['username']) ?>" required>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ls-1">Ganti Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-key text-secondary"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-0 rounded-end" placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <small class="text-muted small">Minimal 6 karakter</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Simpan (melekat di bawah form) -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 py-3">
        <p class="text-muted small mb-0">Pastikan data sudah benar sebelum menyimpan.</p>
        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i> Simpan Perubahan Profil
        </button>
    </div>
</form>
<?= $this->endSection() ?>
