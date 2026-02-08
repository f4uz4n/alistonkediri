<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row mb-4 animate__animated animate__fadeIn">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1">Cek List & Kelengkapan</h2>
        <p class="text-muted mb-0">Verifikasi berkas dan kelola pengambilan perlengkapan untuk <strong><?= esc($participant['name']) ?></strong></p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <?php if (!empty($is_owner)): ?>
        <a href="<?= base_url('owner/participant/kelola/' . $participant['id']) ?>" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Kelola
        </a>
        <?php else: ?>
        <a href="<?= base_url('agency/participants') ?>" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4 animate__animated animate__fadeInUp">
    <!-- Left Column: Data Completeness & Documents -->
    <div class="col-lg-7">
        <!-- Biodata Checklist -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary-light text-primary rounded-3 me-3">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h5 class="card-title mb-0 fw-bold">Kelengkapan Isian Data</h5>
                </div>
            </div>
            <div class="card-body">
                <?php 
                    $fields = [
                        'NIK' => $participant['nik'],
                        'Tempat Lahir' => $participant['place_of_birth'],
                        'Tanggal Lahir' => $participant['date_of_birth'],
                        'Jenis Kelamin' => $participant['gender'],
                        'Alamat' => $participant['address'],
                        'Kecamatan' => $participant['kecamatan'],
                        'Kabupaten' => $participant['kabupaten'],
                        'Provinsi' => $participant['provinsi'],
                        'No. Passport' => $participant['passport_number'],
                        'Kontak Darurat' => $participant['emergency_phone']
                    ];
                ?>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    <?php foreach ($fields as $label => $value): ?>
                        <div class="col">
                            <div class="d-flex align-items-center p-3 rounded-3 bg-light border border-light h-100">
                                <div class="me-3">
                                    <?php if (!empty($value)): ?>
                                        <div class="icon-sm bg-success text-white rounded-circle">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="icon-sm bg-warning text-white rounded-circle">
                                            <i class="fas fa-exclamation"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold ls-1" style="font-size: 0.65rem;"><?= $label ?></small>
                                    <span class="fw-bold <?= empty($value) ? 'text-warning' : 'text-dark' ?>">
                                        <?= empty($value) ? 'Belum Lengkap' : 'Sudah Terisi' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Progress Berkas Digital (Simplified) -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-success-light text-success rounded-3 me-3">
                        <i class="fas fa-file-shield"></i>
                    </div>
                    <h5 class="card-title mb-0 fw-bold">Progress Berkas Digital</h5>
                </div>
            </div>
            <div class="card-body px-4 pb-4 text-center">
                <div class="d-flex justify-content-between mb-2">
                    <span class="small fw-bold text-dark text-uppercase" style="font-size: 0.7rem;">Status Verifikasi Digital</span>
                    <span class="small fw-bold text-primary"><?= $doc_progress ?>%</span>
                </div>
                <div class="progress mb-4" style="height: 12px; background-color: #f1f3f5;">
                    <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: <?= $doc_progress ?>%"></div>
                </div>
                
                <div class="p-4 rounded-4 bg-light border border-dashed text-center">
                    <div class="display-5 fw-bold text-dark mb-1"><?= $verified_count ?> <span class="text-muted fs-4 fw-normal">/ <?= $total_goal ?></span></div>
                    <p class="text-secondary small mb-0 fw-bold text-uppercase ls-1">Berkas Terverifikasi</p>
                </div>

                <div class="mt-4 pt-2">
                    <a href="<?= !empty($is_owner) ? base_url('owner/participant/documents/'.$participant['id']) : base_url('agency/edit-participant/'.$participant['id']) ?>" class="btn btn-primary rounded-pill px-4 fw-bold w-100">
                        <i class="fas fa-upload me-2"></i>Kelola Berkas Digital
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Verification & Equipment -->
    <div class="col-lg-5">
        <!-- Verification Status Board -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-gradient-premium text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <i class="fas fa-shield-alt position-absolute" style="font-size: 8rem; right: -20px; top: -20px; opacity: 0.1;"></i>
                <h5 class="fw-bold mb-3">Status Verifikasi Jamaah</h5>
                <?php if ($participant['is_verified']): ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-white text-success rounded-circle me-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div>
                            <span class="badge bg-white text-success rounded-pill px-3 mb-1">TERVERIFIKASI</span>
                            <p class="mb-0 small opacity-75">Data telah diperiksa oleh pusat pada <?= !empty($participant['verified_at']) ? date('d M Y', strtotime($participant['verified_at'])) : '-' ?></p>
                        </div>
                    </div>
                    <?php if (!empty($is_owner)): ?>
                    <form action="<?= base_url('owner/verify-participant') ?>" method="post" class="mt-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $participant['id'] ?>">
                        <input type="hidden" name="status" value="pending">
                        <button type="submit" class="btn btn-outline-light btn-sm rounded-pill">Batalkan Verifikasi</button>
                    </form>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-white text-warning rounded-circle me-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div>
                            <span class="badge bg-white text-warning rounded-pill px-3 mb-1">MENUNGGU VERIFIKASI</span>
                            <p class="mb-0 small opacity-75">Lengkapi data dan berkas, lalu verifikasi dari menu ini (admin).</p>
                        </div>
                    </div>
                    <?php if (!empty($is_owner)): ?>
                    <form action="<?= base_url('owner/verify-participant') ?>" method="post" class="mt-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $participant['id'] ?>">
                        <input type="hidden" name="status" value="verified">
                        <button type="submit" class="btn btn-light rounded-pill px-4 fw-bold"><i class="fas fa-check me-2"></i>Verifikasi Pendaftaran Jamaah</button>
                    </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Equipment Collection -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-warning-light text-warning rounded-3 me-3">
                        <i class="fas fa-luggage-cart"></i>
                    </div>
                    <h5 class="card-title mb-0 fw-bold">Pengambilan Kelengkapan</h5>
                </div>
            </div>
            <div class="card-body">
                <?php if (!$participant['is_verified']): ?>
                    <div class="text-center py-5">
                        <div class="mb-3 text-muted">
                            <i class="fas fa-lock fa-3x opacity-25"></i>
                        </div>
                        <h6 class="text-muted fw-bold">Fitur Belum Tersedia</h6>
                        <p class="small text-muted mb-0 px-4">Pengambilan perlengkapan hanya dapat diproses jika data jamaah sudah <strong>Terverifikasi</strong> oleh admin pusat.</p>
                    </div>
                <?php else: ?>
                    <?php if (empty($freebies)): ?>
                        <div class="alert alert-light border-0">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada daftar kelengkapan untuk paket ini.
                        </div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($freebies as $item): ?>
                                <li class="list-group-item d-flex align-items-center justify-content-between py-3 border-light">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-box-open text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block"><?= esc($item) ?></span>
                                            <?php if (isset($collectedMap[$item])): ?>
                                                <small class="text-success fw-bold">Diambil: <?= date('d M Y', strtotime($collectedMap[$item]['collected_at'])) ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">Belum diambil</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input equipment-toggle" type="checkbox" 
                                            data-item="<?= esc($item) ?>" 
                                            data-participant="<?= $participant['id'] ?>"
                                            <?= isset($collectedMap[$item]) && $collectedMap[$item]['status'] === 'collected' ? 'checked' : '' ?>>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-light border-0 py-3 rounded-bottom-4">
                <p class="small text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Centang item untuk menandai bahwa jamaah telah mengambil perlengkapan tersebut.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-premium {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }
    .icon-box {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .bg-primary-light { background-color: rgba(239, 51, 56, 0.1); }
    .bg-success-light { background-color: rgba(0, 166, 81, 0.1); }
    .bg-warning-light { background-color: rgba(255, 193, 7, 0.1); }
    
    .icon-sm {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }
    .object-fit-cover { object-fit: cover; }
    .ls-1 { letter-spacing: 0.5px; }

    .doc-preview-card {
        transition: all 0.3s ease;
        background-color: #fff;
    }
    .doc-preview-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: var(--primary-color) !important;
    }

    .form-check-input:checked {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.equipment-toggle');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const itemName = this.dataset.item;
            const participantId = this.dataset.participant;
            const isChecked = this.checked;
            
            // Show loading state
            this.disabled = true;
            
            fetch('<?= base_url('agency/toggle-equipment') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `participant_id=${participantId}&item_name=${encodeURIComponent(itemName)}&status=${isChecked ? 'collected' : 'pending'}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Optional: show toast
                    location.reload(); // Refresh to update timestamps
                } else {
                    alert('Gagal memperbarui status: ' + data.message);
                    this.checked = !isChecked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan jaringan');
                this.checked = !isChecked;
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
});
</script>
<?= $this->endSection() ?>
