<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Daftar Agensi</h2>
        <p class="text-secondary">Kelola kemitraan agensi Aliston Anda</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <a href="<?= base_url('owner/agency/create') ?>" class="btn-premium d-inline-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i> Daftarkan Agensi Baru
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-3">
                <form action="<?= base_url('owner/agency') ?>" method="get" class="row g-3">
                    <div class="col-12 col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search text-secondary"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari nama atau username agensi..." value="<?= esc($search ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-filter text-secondary"></i></span>
                            <select name="status" class="form-select bg-light border-0">
                                <option value="">Semua Status</option>
                                <option value="1" <?= ($status ?? '') === '1' ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= ($status ?? '') === '0' ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                                Cari & Filter
                            </button>
                            <?php if(!empty($search) || ($status ?? '') !== ''): ?>
                                <a href="<?= base_url('owner/agency') ?>" class="btn btn-light rounded-pill fw-bold border">
                                    Reset
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-secondary small fw-bold text-uppercase">Profil Agensi</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase">Kontak & Terdaftar</th>
                            <th class="py-3 border-0 text-secondary small fw-bold text-uppercase text-center">Status</th>
                            <th class="pe-4 py-3 border-0 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($agencies)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-people text-muted fs-1 mb-3"></i>
                                        <p class="text-secondary mb-0">Belum ada agensi yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($agencies as $agency): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <?php if($agency['profile_pic']): ?>
                                            <img src="<?= base_url($agency['profile_pic']) ?>" alt="Profile" class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid var(--primary-soft);">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-primary-soft text-primary p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                <i class="bi bi-person-badge fs-5"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <span class="fw-bold text-dark d-block"><?= esc($agency['full_name'] ?: $agency['username']) ?></span>
                                            <small class="text-secondary fw-500">@<?= esc($agency['username']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="text-dark small fw-bold"><i class="bi bi-whatsapp text-success me-1"></i> <?= esc($agency['phone'] ?: '-') ?></span>
                                        <span class="text-secondary smaller"><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($agency['created_at'])) ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex flex-column align-items-center justify-content-center">
                                        <input class="form-check-input status-toggle mb-1" type="checkbox" 
                                               id="status_<?= $agency['id'] ?>" 
                                               data-id="<?= $agency['id'] ?>"
                                               <?= $agency['is_active'] ? 'checked' : '' ?>
                                               style="width: 40px; height: 20px; cursor: pointer;">
                                        <small class="fw-bold status-label <?= $agency['is_active'] ? 'text-success' : 'text-danger' ?>" id="label_<?= $agency['id'] ?>" style="font-size: 0.65rem;">
                                            <?= $agency['is_active'] ? 'AKTIF' : 'NONAKTIF' ?>
                                        </small>
                                    </div>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?= base_url('owner/agency/edit/'.$agency['id']) ?>" class="btn btn-light btn-sm rounded-pill px-3 fw-bold border shadow-sm">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
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

<script>
document.querySelectorAll('.status-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const agencyId = this.dataset.id;
        const label = document.getElementById('label_' + agencyId);
        const originalStatus = !this.checked;

        const formData = new FormData();
        // CI4 CSRF token if needed, usually handled globally if using forms, 
        // but for fetch we can pass it if security is strict.
        
        fetch('<?= base_url('owner/agency/toggle-status/') ?>' + agencyId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                label.innerText = data.new_status ? 'AKTIF' : 'NONAKTIF';
                label.className = `fw-bold status-label ${data.new_status ? 'text-success' : 'text-danger'}`;
            } else {
                this.checked = originalStatus;
                alert('Gagal mengubah status: ' + data.message);
            }
        })
        .catch(error => {
            this.checked = originalStatus;
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem.');
        });
    });
});
</script>
<?= $this->endSection() ?>
