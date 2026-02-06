<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row mb-5">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Tim Saya</h1>
            <p class="text-secondary mb-0">Kelola anggota agensi dan jamaah Anda</p>
        </div>
        <a href="<?= base_url('agency/add_member') ?>" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i> Tambah Anggota
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase border-bottom-0">Nama</th>
                        <th class="py-3 text-secondary small fw-bold text-uppercase border-bottom-0">Info Kontak</th>
                        <th class="py-3 text-secondary small fw-bold text-uppercase border-bottom-0 text-end pe-4">Tanggal Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($members)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="text-secondary mb-2">
                                    <i class="bi bi-people fs-1"></i>
                                </div>
                                <p class="text-muted mb-0">Belum ada anggota tim.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($members as $member): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><?= esc($member['name']) ?></h6>
                                        <small class="text-secondary">ID: #<?= $member['id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="mailto:<?= esc($member['email']) ?>" class="text-decoration-none text-secondary mb-1">
                                        <i class="bi bi-envelope me-2"></i><?= esc($member['email']) ?>
                                    </a>
                                    <span class="text-secondary">
                                        <i class="bi bi-telephone me-2"></i><?= esc($member['phone']) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="text-end pe-4 text-secondary">
                                <?= date('M d, Y', strtotime($member['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
