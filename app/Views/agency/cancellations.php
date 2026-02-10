<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Pembatalan</h2>
        <p class="text-secondary mb-0">Daftar jamaah yang dibatalkan beserta catatan refund.</p>
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
                            <th class="border-0 py-3">Nama</th>
                            <th class="border-0 py-3">Paket</th>
                            <th class="border-0 py-3">Total Dibayar</th>
                            <th class="border-0 py-3">Refund (Rp)</th>
                            <th class="border-0 py-3">Tgl. Batal</th>
                            <th class="border-0 py-3">Catatan</th>
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
                                <div class="small text-muted"><?= esc($row['nik'] ?? '') ?></div>
                            </td>
                            <td><?= esc($row['package_name'] ?? '—') ?></td>
                            <td>Rp <?= number_format($row['total_paid'] ?? 0, 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($row['refund_amount'] ?? 0, 0, ',', '.') ?></td>
                            <td><?= !empty($row['cancelled_at']) ? date('d/m/Y H:i', strtotime($row['cancelled_at'])) : '—' ?></td>
                            <td class="small text-muted"><?= esc($notesShort) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
