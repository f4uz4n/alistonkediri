<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6">
        <a href="<?= base_url('owner/participant') ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-2">
            <i class="bi bi-arrow-left me-2"></i>Daftar Jamaah
        </a>
        <h2 class="fw-800 text-dark mb-1">Registrasi dari Kantor</h2>
        <p class="text-secondary mb-0">Pendaftaran jamaah langsung dari kantor. Pilih paket lalu isi data jamaah.</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-light border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-briefcase-fill me-2"></i>Pilih Paket Perjalanan</h6>
    </div>
    <div class="card-body p-0">
        <?php if (empty($packages)): ?>
            <div class="p-5 text-center text-muted">
                <i class="bi bi-briefcase display-4"></i>
                <p class="mt-2 mb-0">Tidak ada paket yang dapat menerima pendaftaran.</p>
                <p class="small mb-0">Paket expired atau kuota penuh tidak ditampilkan. Pastikan ada paket aktif dengan tanggal keberangkatan belum lewat.</p>
            </div>
        <?php else: ?>
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 py-3">Paket</th>
                        <th class="border-0 py-3">Keberangkatan</th>
                        <th class="border-0 py-3">Harga</th>
                        <th class="border-0 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $pkg): ?>
                    <tr>
                        <td><strong><?= esc($pkg['name']) ?></strong></td>
                        <td><?= !empty($pkg['departure_date']) ? date('d/m/Y', strtotime($pkg['departure_date'])) : '—' ?></td>
                        <td>Rp <?= number_format($pkg['price'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-end">
                            <a href="<?= base_url('owner/participant/register/' . $pkg['id']) ?>" class="btn btn-primary btn-sm rounded-pill"><i class="bi bi-person-plus me-1"></i> Daftarkan Jamaah</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
