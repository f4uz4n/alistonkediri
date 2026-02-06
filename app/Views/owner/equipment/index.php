<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Master Atribut & Souvenir</h2>
        <p class="text-secondary">Kelola daftar perlengkapan yang akan diberikan ke jamaah</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Atribut
        </button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-secondary small fw-bold text-uppercase">Nama Atribut</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase">Deskripsi</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Status</th>
                            <th class="pe-4 py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($items)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <p class="text-secondary mb-0">Belum ada data atribut.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($items as $item): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark"><?= esc($item['name']) ?></span>
                                </td>
                                <td>
                                    <span class="text-secondary small"><?= esc($item['description'] ?: '-') ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if($item['is_active']): ?>
                                        <span class="badge bg-success-soft text-success rounded-pill px-3 py-2 fw-bold">AKTIF</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-soft text-secondary rounded-pill px-3 py-2 fw-bold">NONAKTIF</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?= base_url('owner/equipment/toggle/'.$item['id']) ?>" class="btn btn-light btn-sm rounded-circle p-2 border" title="<?= $item['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                            <i class="bi <?= $item['is_active'] ? 'bi-toggle-on text-primary' : 'bi-toggle-off' ?> fs-5"></i>
                                        </a>
                                        <button type="button" class="btn btn-light btn-sm rounded-circle p-2 border" data-bs-toggle="modal" data-bs-target="#editModal<?= $item['id'] ?>">
                                            <i class="bi bi-pencil-square text-info"></i>
                                        </button>
                                        <a href="<?= base_url('owner/equipment/delete/'.$item['id']) ?>" class="btn btn-light btn-sm rounded-circle p-2 border" onclick="return confirm('Hapus atribut ini?')">
                                            <i class="bi bi-trash text-danger"></i>
                                        </a>
                                    </div>
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

<!-- All Modals (Placed at fixed position to avoid rendering issues) -->

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 p-4">
                <h5 class="fw-bold mb-0">Tambah Atribut Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('owner/equipment/store') ?>" method="post">
                <div class="modal-body p-4 pt-0">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Atribut</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Contoh: Koper Aliston" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Detail perlengkapan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(!empty($items)): ?>
    <?php foreach($items as $item): ?>
    <!-- Edit Modal for <?= esc($item['name']) ?> -->
    <div class="modal fade" id="editModal<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 p-4">
                    <h5 class="fw-bold mb-0">Edit Atribut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('owner/equipment/update/'.$item['id']) ?>" method="post">
                    <div class="modal-body p-4 pt-0">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Atribut</label>
                            <input type="text" name="name" class="form-control rounded-3" value="<?= esc($item['name']) ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">Deskripsi</label>
                            <textarea name="description" class="form-control rounded-3" rows="3"><?= esc($item['description']) ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
