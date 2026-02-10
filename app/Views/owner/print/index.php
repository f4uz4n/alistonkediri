<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-5">
    <div class="col-12 col-md-6">
        <h2 class="fw-800 text-dark mb-1">Cetak Dokumen</h2>
        <p class="text-secondary">Pilih dokumen yang ingin dicetak</p>
    </div>
</div>

<div class="row g-4">
    <!-- Surat Izin Cuti -->
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary-soft rounded-circle p-3 me-3">
                        <i class="bi bi-file-earmark-text-fill text-primary fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Surat Izin Cuti</h5>
                        <p class="text-secondary small mb-0">Cetak surat izin cuti untuk jamaah</p>
                    </div>
                </div>
                <form action="<?= base_url('owner/print-documents/leave-letter') ?>" method="get" target="_blank">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Pilih Jamaah</label>
                        <select name="participant_id" class="form-select bg-light border-0" required>
                            <option value="">-- Pilih Jamaah --</option>
                            <?php foreach($participants as $p): ?>
                                <option value="<?= $p['id'] ?>">
                                    <?= esc($p['name']) ?> 
                                    <?php if (!empty($p['nik'])): ?>
                                        (NIK: <?= esc($p['nik']) ?>)
                                    <?php endif; ?>
                                    — <?= esc($p['package_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold w-100">
                        <i class="bi bi-printer-fill me-2"></i> Cetak Surat Izin Cuti
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background: rgba(13, 110, 253, 0.1); }
</style>
<?= $this->endSection() ?>
