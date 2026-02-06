<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-800 text-dark mb-1">Buat Paket Perjalanan</h2>
                <p class="text-secondary mb-0">Lengkapi detail paket untuk dipromosikan oleh agensi Anda</p>
            </div>
            <a href="<?= base_url('package') ?>" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <form action="<?= base_url('package/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Section 1: Informasi Dasar -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-soft text-primary rounded-circle p-2 me-3">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Informasi Umum</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Nama Paket Perjalanan</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: UMROH SYAWAL 13 HARI" required>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Durasi Paket</label>
                            <input type="text" name="duration" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: 13 Hari" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Gambar Paket (Brosur/Pemandangan)</label>
                            <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Tanggal Berangkat</label>
                            <input type="date" name="departure_date" class="form-control form-control-lg bg-light border-0" required>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Lokasi Start - End</label>
                            <input type="text" name="location_start_end" class="form-control form-control-lg bg-light border-0" placeholder="KEDIRI" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Akomodasi -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-success-soft text-secondary-color rounded-circle p-2 me-3">
                            <i class="bi bi-building-fill fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Penginapan & Akomodasi</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Hotel Mekkah</label>
                            <input type="text" name="hotel_mekkah" class="form-control bg-light border-0 mb-2" placeholder="Nama Hotel" required>
                            <select name="hotel_mekkah_stars" class="form-select bg-light border-0">
                                <option value="3">3 Bintang</option>
                                <option value="4" selected>4 Bintang</option>
                                <option value="5">5 Bintang</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Hotel Madinah</label>
                            <input type="text" name="hotel_madinah" class="form-control bg-light border-0 mb-2" placeholder="Nama Hotel" required>
                            <select name="hotel_madinah_stars" class="form-select bg-light border-0">
                                <option value="3">3 Bintang</option>
                                <option value="4" selected>4 Bintang</option>
                                <option value="5">5 Bintang</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Inklusi & Freebies -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning-soft text-warning rounded-circle p-2 me-3">
                            <i class="bi bi-gift-fill fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Sudah Termasuk & Fasilitas Tambahan</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1 mb-2">Sudah Termasuk (Satu per baris)</label>
                            <textarea name="inclusions" class="form-control bg-light border-0" rows="8" placeholder="Tiket Pesawat PP&#10;Visa Umroh..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1 mb-2">Gratis / Free (Satu per baris)</label>
                            <textarea name="freebies" class="form-control bg-light border-0" rows="8" placeholder="City Tour Thaif..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Transportasi & Harga -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-info-soft text-info rounded-circle p-2 me-3">
                            <i class="bi bi-airplane-engines-fill fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Transportasi & Harga</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Maskapai</label>
                            <input type="text" name="airline" class="form-control bg-light border-0" placeholder="Lion Air" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Rute</label>
                            <input type="text" name="flight_route" class="form-control bg-light border-0" placeholder="SUB - JED" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Harga (Nominal)</label>
                            <input type="number" step="0.01" name="price" class="form-control bg-light border-0" placeholder="31.9" required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Unit</label>
                            <input type="text" name="price_unit" class="form-control bg-light border-0 text-center fw-bold" value="JT" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end mb-5">
                <button type="submit" class="btn-premium rounded-pill px-5">
                    <i class="bi bi-check2-circle me-2"></i>Simpan Paket Perjalanan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>