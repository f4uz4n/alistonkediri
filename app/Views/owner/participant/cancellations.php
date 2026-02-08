<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6">
        <a href="<?= base_url('owner/participant') ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-2">
            <i class="bi bi-arrow-left me-2"></i>Daftar Jamaah
        </a>
        <h2 class="fw-800 text-dark mb-1">Pembatalan</h2>
        <p class="text-secondary mb-0">Daftar jamaah yang dibatalkan beserta catatan refund.</p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <?php if (empty($list)): ?>
            <div class="p-5 text-center text-muted">
                <i class="bi bi-inbox display-4"></i>
                <p class="mt-2 mb-0">Belum ada data pembatalan.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3">Nama / Agensi</th>
                            <th class="border-0 py-3">Paket</th>
                            <th class="border-0 py-3">Total Dibayar</th>
                            <th class="border-0 py-3">Refund (Rp)</th>
                            <th class="border-0 py-3">Tgl. Batal</th>
                            <th class="border-0 py-3">Catatan</th>
                            <th class="border-0 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $row): 
                            $notes = $row['cancellation_notes'] ?? '';
                            $notesShort = strlen($notes) > 60 ? substr($notes, 0, 60) . '…' : $notes;
                        ?>
                        <tr>
                            <td>
                                <strong><?= esc($row['name']) ?></strong>
                                <div class="small text-muted"><?= esc($row['agency_name'] ?? '') ?></div>
                            </td>
                            <td><?= esc($row['package_name'] ?? '—') ?></td>
                            <td>Rp <?= number_format($row['total_paid'] ?? 0, 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($row['refund_amount'] ?? 0, 0, ',', '.') ?></td>
                            <td><?= !empty($row['cancelled_at']) ? date('d/m/Y H:i', strtotime($row['cancelled_at'])) : '—' ?></td>
                            <td class="small text-muted"><?= esc($notesShort) ?></td>
                            <td>
                                <a href="<?= base_url('owner/participant/kelola/' . $row['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill">Kelola</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
