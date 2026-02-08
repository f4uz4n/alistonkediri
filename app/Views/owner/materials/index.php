<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Materi Promosi</h2>
        <p class="text-secondary mb-0">Kelola aset pemasaran untuk agensi Anda</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <a href="<?= base_url('owner/materials/create') ?>" class="btn-premium d-inline-flex align-items-center gap-2">
            <i class="bi bi-cloud-arrow-up-fill"></i> Tambah Materi Baru
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-secondary small fw-bold text-uppercase">Informasi Materi</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase">Tipe</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase">Tanggal Upload</th>
                            <th class="pe-4 py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($materials)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-folder-x text-muted fs-1 mb-3"></i>
                                        <p class="text-secondary mb-0">Belum ada materi promosi yang diunggah.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($materials as $material): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary-soft text-primary p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi <?= $material['type'] == 'youtube' ? 'bi-youtube' : ($material['type'] == 'file' ? 'bi-file-earmark-text' : 'bi-link-45deg') ?> fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block"><?= esc($material['title']) ?></span>
                                            <small class="text-secondary smaller"><?= esc(substr($material['description'], 0, 50)) ?><?= strlen($material['description']) > 50 ? '...' : '' ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 small fw-bold">
                                        <?= strtoupper($material['type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-secondary small"><?= date('d M Y', strtotime($material['created_at'])) ?></span>
                                </td>
                                <td class="pe-4 text-end">
                                    <?php if($material['type'] == 'file'): ?>
                                        <a href="<?= base_url($material['file_path']) ?>" target="_blank" class="btn btn-primary-soft btn-sm rounded-pill px-3 fw-bold">
                                            <i class="bi bi-download me-1"></i> Download
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= esc($material['url']) ?>" target="_blank" class="btn btn-primary-soft btn-sm rounded-pill px-3 fw-bold">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('owner/materials/edit/' . $material['id']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <a href="<?= base_url('owner/materials/delete/' . $material['id']) ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold" onclick="return confirm('Yakin ingin menghapus materi ini?');">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </a>
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
<?= $this->endSection() ?>