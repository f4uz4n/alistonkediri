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

<!-- Filter -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?= base_url('owner/participant/cancellations') ?>" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold text-secondary">Cari nama jamaah</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search text-secondary"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Nama atau NIK jamaah..." value="<?= esc($search ?? '') ?>">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small fw-bold text-secondary">Tgl. batal dari</label>
                <input type="date" name="date_from" class="form-control bg-light border-0" value="<?= esc($date_from ?? '') ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small fw-bold text-secondary">Tgl. batal sampai</label>
                <input type="date" name="date_to" class="form-control bg-light border-0" value="<?= esc($date_to ?? '') ?>">
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="<?= base_url('owner/participant/cancellations') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 w-100 mt-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <?php if (empty($list)): ?>
            <div class="p-5 text-center text-muted">
                <i class="bi bi-inbox display-4"></i>
                <p class="mt-2 mb-0">Belum ada data pembatalan.</p>
                <?php if (!empty($search) || !empty($date_from) || !empty($date_to)): ?>
                <a href="<?= base_url('owner/participant/cancellations') ?>" class="btn btn-light rounded-pill mt-3">Tampilkan semua</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 py-3 ps-4 small fw-bold text-secondary text-uppercase">Nama / Agensi</th>
                    <th class="border-0 py-3 small fw-bold text-secondary text-uppercase">Paket</th>
                    <th class="border-0 py-3 small fw-bold text-secondary text-uppercase text-end">Total Dibayar</th>
                    <th class="border-0 py-3 small fw-bold text-secondary text-uppercase text-end">Refund (Rp)</th>
                    <th class="border-0 py-3 small fw-bold text-secondary text-uppercase">Tgl. Batal</th>
                    <th class="border-0 py-3 small fw-bold text-secondary text-uppercase" style="max-width: 180px;">Catatan</th>
                    <th class="border-0 py-3 pe-4 text-end small fw-bold text-secondary text-uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row):
                    $notes = $row['cancellation_notes'] ?? '';
                    $notesShort = strlen($notes) > 50 ? substr($notes, 0, 50) . '…' : $notes;
                ?>
                <tr>
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-dark"><?= esc($row['name']) ?></div>
                        <div class="small text-muted"><?= esc($row['agency_name'] ?? '—') ?></div>
                    </td>
                    <td class="py-3"><span class="text-dark"><?= esc($row['package_name'] ?? '—') ?></span></td>
                    <td class="py-3 text-end"><span class="text-dark">Rp <?= number_format($row['total_paid'] ?? 0, 0, ',', '.') ?></span></td>
                    <td class="py-3 text-end"><span class="fw-semibold text-dark">Rp <?= number_format($row['refund_amount'] ?? 0, 0, ',', '.') ?></span></td>
                    <td class="py-3">
                        <span class="text-dark"><?= !empty($row['cancelled_at']) ? date('d/m/Y', strtotime($row['cancelled_at'])) : '—' ?></span>
                        <?php if (!empty($row['cancelled_at'])): ?><br><span class="small text-muted"><?= date('H:i', strtotime($row['cancelled_at'])) ?></span><?php endif; ?>
                    </td>
                    <td class="py-3 small text-muted" style="max-width: 180px;"><?= esc($notesShort) ?></td>
                    <td class="pe-4 py-3 text-end">
                        <div class="d-flex flex-wrap gap-1 justify-content-end">
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill btn-reactivate" data-id="<?= (int)$row['id'] ?>" data-name="<?= esc($row['name']) ?>" data-refund="<?= (float)($row['refund_amount'] ?? 0) ?>">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Aktifkan Kembali
                            </button>
                            <a href="<?= base_url('owner/participant/cancellation-statement/' . $row['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill" title="Cetak surat pernyataan"><i class="bi bi-file-earmark-text me-1"></i> Cetak Surat</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Aktifkan Kembali Jamaah -->
<div class="modal fade" id="reactivateModal" tabindex="-1" aria-labelledby="reactivateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="reactivateModalLabel"><i class="bi bi-arrow-counterclockwise me-2"></i>Aktifkan Kembali Jamaah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="formReactivate">
                <?= csrf_field() ?>
                <input type="hidden" name="participant_id" id="reactivateParticipantId" value="">
                <div class="modal-body">
                    <p class="text-secondary mb-3" id="reactivateJamaahName"></p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal yang sudah di-refund (Rp)</label>
                        <input type="number" name="refund_amount_display" id="reactivateRefundAmount" class="form-control" min="0" step="0.01" placeholder="0" required>
                        <div class="form-text">Isi nominal yang telah ditransfer ke jamaah (untuk konfirmasi).</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill"><i class="bi bi-check-circle me-1"></i> Aktifkan Kembali Jamaah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-reactivate').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var name = this.getAttribute('data-name');
        var refund = parseFloat(this.getAttribute('data-refund')) || 0;
        document.getElementById('reactivateParticipantId').value = id;
        document.getElementById('reactivateJamaahName').textContent = 'Jamaah: ' + name;
        document.getElementById('reactivateRefundAmount').value = refund > 0 ? refund : '';
        document.getElementById('formReactivate').action = '<?= base_url('owner/participant/reactivate/') ?>' + id;
        new bootstrap.Modal(document.getElementById('reactivateModal')).show();
    });
});
</script>
<?= $this->endSection() ?>
