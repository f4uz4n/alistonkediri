<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6">
        <a href="<?= base_url('owner/participant/kelola/' . $participant['id']) ?>" class="btn btn-light border rounded-pill px-3 fw-bold mb-2">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Kelola Jamaah
        </a>
        <h2 class="fw-800 text-dark mb-1">Tambah Pembayaran (dari Kantor)</h2>
        <p class="text-secondary mb-0"><?= esc($participant['name']) ?> &mdash; <?= esc($participant['agency_name']) ?></p>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-pill px-4 py-2 d-inline-block"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-light border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-cash-stack me-2"></i>Input Pembayaran</h6>
            </div>
            <div class="card-body">
                <p class="text-secondary small mb-2">Total dibayar: <strong class="text-primary">Rp <?= number_format($total_paid, 0, ',', '.') ?></strong> &nbsp;|&nbsp; Target: <strong>Rp <?= number_format($total_target, 0, ',', '.') ?></strong></p>
                <form action="<?= base_url('owner/participant/store-payment-office') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="participant_id" value="<?= $participant['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal (Rp)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0">Rp</span>
                            <input type="text" id="amountDisplay" class="form-control form-control-lg bg-light border-0 rounded-end" placeholder="0" autocomplete="off" required>
                            <input type="hidden" name="amount" id="amountValue" value="<?= esc(old('amount')) ?>">
                        </div>
                        <div class="form-text">Ketik nominal tanpa titik atau koma</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" class="form-control" value="<?= esc(old('payment_date', date('Y-m-d'))) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan (opsional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Pembayaran tunai di kantor"><?= esc(old('notes')) ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Bukti pembayaran (opsional)</label>
                        <input type="file" name="proof" class="form-control" accept="image/*,.pdf">
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-check2 me-1"></i> Simpan Pembayaran</button>
                    <a href="<?= base_url('owner/participant/kelola/' . $participant['id']) ?>" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var display = document.getElementById('amountDisplay');
    var hidden = document.getElementById('amountValue');
    var oldVal = hidden.value ? String(hidden.value).replace(/\D/g, '') : '';

    function formatRupiah(n) {
        var u = (n || '').replace(/\D/g, '');
        return u.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function parseRupiah(s) {
        return (s || '').replace(/\D/g, '');
    }

    if (oldVal) {
        display.value = formatRupiah(oldVal);
    }

    display.addEventListener('input', function() {
        var raw = parseRupiah(this.value);
        hidden.value = raw;
        this.value = formatRupiah(raw);
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        var raw = parseRupiah(display.value);
        hidden.value = raw;
        if (!raw || parseInt(raw, 10) < 1) {
            e.preventDefault();
            display.focus();
            return false;
        }
    });
})();
</script>
<?= $this->endSection() ?>
