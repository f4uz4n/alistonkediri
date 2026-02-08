<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Melengkapi Berkas</h2>
        <p class="text-secondary mb-0"><?= esc($participant['name']) ?> · <?= esc($package['name'] ?? '-') ?></p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <a href="<?= base_url('agency/participants') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Jamaah
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="bi bi-person-fill fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1"><?= esc($participant['name']) ?></h5>
                <p class="text-secondary small mb-0"><?= esc($participant['nik']) ?></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">Kelola Berkas Digital</h5>
                <span class="badge bg-light text-dark border rounded-pill px-3"><?= count($documents) ?> Berkas</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0">Judul / Jenis</th>
                                <th class="border-0">Upload</th>
                                <th class="border-0">Status</th>
                                <th class="pe-4 border-0 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($documents)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">Belum ada berkas digital.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3">
                                                <i class="bi bi-file-earmark-pdf fs-4"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block"><?= esc($doc['title'] ?: strtoupper(str_replace('_', ' ', $doc['type']))) ?></span>
                                                <small class="text-muted text-uppercase" style="font-size: 0.6rem;"><?= str_replace('_', ' ', $doc['type']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small text-secondary"><?= date('d/m/y', strtotime($doc['created_at'])) ?></td>
                                    <td>
                                        <?php if (!empty($doc['is_verified'])): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold">Terverifikasi</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1 fw-bold">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="<?= base_url($doc['file_path']) ?>" target="_blank" class="btn btn-light btn-sm rounded-pill px-3 border"><i class="bi bi-eye me-1"></i> Lihat</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white py-4 px-4 border-0">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-cloud-arrow-up me-2"></i>Upload Berkas Baru</h5>
    </div>
    <div class="card-body p-4 p-md-5">
        <form action="<?= base_url('agency/upload-document') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="participant_id" value="<?= (int)$participant['id'] ?>">
            <div id="uploadContainer">
                <div class="upload-row mb-4 border-bottom pb-4 position-relative">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small text-uppercase text-secondary fw-bold">Jenis Berkas</label>
                            <select name="types[]" class="form-select form-select-lg bg-light border-0 rounded-3" required>
                                <option value="passport">Paspor</option>
                                <option value="id_card">KTP</option>
                                <option value="vaccine">Kartu Vaksin</option>
                                <option value="visa">Visa</option>
                                <option value="vaccine_meningitis">Vaksin Meningitis</option>
                                <option value="vaccine_covid">Vaksin Covid</option>
                                <option value="insurance">Asuransi</option>
                                <option value="ticket">Tiket</option>
                                <option value="photo">Pas Foto 4x6</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small text-uppercase text-secondary fw-bold">Judul (Opsional)</label>
                            <input type="text" name="titles[]" class="form-control form-control-lg bg-light border-0 rounded-3" placeholder="Visa / Vaksin Meningitis / dll">
                        </div>
                        <div class="col-md-2 d-flex align-items-end pb-2">
                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill w-100 remove-row" style="display:none;"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-uppercase text-secondary fw-bold">File</label>
                            <input type="file" name="files[]" class="form-control bg-light border-0 rounded-3" required accept=".pdf,image/*">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-light rounded-pill px-4 fw-bold border mb-3 py-2" id="btnAddUpload">
                <i class="bi bi-plus-lg me-1"></i> Tambah Berkas
            </button>
            <div>
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">
                    <i class="bi bi-cloud-upload me-2"></i> Mulai Upload
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btnAddUpload = document.getElementById('btnAddUpload');
    var uploadContainer = document.getElementById('uploadContainer');
    
    function toggleRemoveButtons() {
        var rows = uploadContainer.querySelectorAll('.upload-row');
        rows.forEach(function(row, i) {
            var btn = row.querySelector('.remove-row');
            if (btn) btn.style.display = rows.length > 1 ? 'block' : 'none';
        });
    }
    
    uploadContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.upload-row').remove();
            toggleRemoveButtons();
        }
    });
    
    btnAddUpload.addEventListener('click', function() {
        var firstRow = document.querySelector('.upload-row');
        var newRow = firstRow.cloneNode(true);
        newRow.querySelectorAll('input').forEach(function(i) { i.value = ''; });
        newRow.querySelector('select').selectedIndex = 0;
        var removeBtn = newRow.querySelector('.remove-row');
        if (removeBtn) removeBtn.style.display = 'block';
        uploadContainer.appendChild(newRow);
        toggleRemoveButtons();
    });
    
    toggleRemoveButtons();
});
</script>
<?= $this->endSection() ?>
