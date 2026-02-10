<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-800 text-dark mb-1">Daftarkan Agensi</h2>
                <p class="text-secondary mb-0">Lengkapi data profil agensi Aliston yang baru</p>
            </div>
            <a href="<?= base_url('owner/agency') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <form action="<?= base_url('owner/agency/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row g-4">
                <!-- Data Profil -->
                <div class="col-12 col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                        <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-soft text-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-person-bounding-box fs-5"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">Informasi Profil Agensi</h5>
                            </div>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Nama Lengkap Agensi / Pemilik</label>
                                    <input type="text" name="full_name" class="form-control form-control-lg bg-light border-0" placeholder="Masukkan nama lengkap" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Nomor WhatsApp / HP</label>
                                    <input type="text" name="phone" class="form-control form-control-lg bg-light border-0" placeholder="08xxxxxxxx" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Email (Optional)</label>
                                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0" placeholder="email@contoh.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Alamat Lengkap</label>
                                    <textarea name="address" class="form-control bg-light border-0" rows="4" placeholder="Jl. Raya Nomor 123..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Nomor Rekening</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-bank"></i></span>
                                        <input type="text" name="nomor_rekening" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: 1234567890">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Nama Bank</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-building"></i></span>
                                        <input type="text" name="nama_bank" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: BCA, Mandiri, BRI">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Foto Profil / Logo Agensi</label>
                                    <div class="p-4 border-2 border-dashed rounded-4 text-center bg-light">
                                        <i class="bi bi-cloud-arrow-up fs-1 text-primary opacity-50 d-block mb-2"></i>
                                        <input type="file" name="profile_pic" class="form-control bg-white border" accept="image/*">
                                        <small class="text-muted d-block mt-2 font-outfit small">Format: JPG, PNG, WEBP. Ukuran Maks: 2MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Akun (Login) -->
                <div class="col-12 col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white border-top border-4 border-primary">
                        <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-success-soft text-secondary-color rounded-circle p-2 me-3">
                                    <i class="bi bi-shield-lock-fill fs-5"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">Keamanan & Akses Akun</h5>
                            </div>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Username Login</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-at"></i></span>
                                    <input type="text" name="username" class="form-control form-control-lg bg-light border-0" placeholder="username_agensi" required>
                                </div>
                                <small class="text-muted mt-2 d-block smaller">Hanya huruf kecil, angka, dan underscore (_)</small>
                            </div>
                            <div class="mb-5">
                                <label class="form-label fw-bold small text-uppercase text-secondary ls-1 font-outfit">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control form-control-lg bg-light border-0" placeholder="Min. 6 karakter" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn-premium w-100 rounded-pill py-3 shadow-lg">
                                <i class="bi bi-person-check-fill me-2"></i>Aktifkan Agensi Sekarang
                            </button>
                            
                            <div class="mt-4 p-3 bg-light rounded-4 border border-light">
                                <p class="small text-secondary mb-0">
                                    <i class="bi bi-info-circle me-1"></i> Setelah didaftarkan, agensi dapat langsung masuk menggunakan username dan password di atas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>