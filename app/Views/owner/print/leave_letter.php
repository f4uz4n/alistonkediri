<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin Cuti — <?= esc($participant['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page { size: A4; margin: 20mm; }
        @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 11pt; color: #1e293b; background: #fff; padding: 20px; }
        .print-sheet { max-width: 210mm; margin: 0 auto; padding: 0; }
        .kop {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo { max-height: 50px; max-width: 150px; object-fit: contain; margin-bottom: 5px; }
        .kop-nama { font-weight: 800; font-size: 1.1rem; color: #1e293b; margin: 0 0 3px 0; letter-spacing: -0.02em; }
        .kop-alamat { font-size: 0.75rem; color: #64748b; margin: 0; line-height: 1.4; }
        .doc-title { font-weight: 800; font-size: 1rem; text-align: center; margin-bottom: 25px; color: #0f172a; text-transform: uppercase; }
        .content { line-height: 1.8; text-align: justify; }
        .content p { margin-bottom: 12px; }
        .content .indent { padding-left: 30px; }
        .signature-block { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature-left, .signature-right { width: 48%; }
        .signature-block .ttd-label { font-size: 9pt; font-weight: 700; color: #64748b; margin-bottom: 60px; }
        .signature-block .ttd-company { font-size: 9pt; font-weight: 600; color: #1e293b; margin-bottom: 5px; }
        .signature-block .nama-pemilik { font-weight: 700; font-size: 10pt; color: #1e293b; margin-top: 5px; }
        .no-print { margin-top: 20px; text-align: center; }
        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
<div class="print-sheet">
    <!-- Kop -->
    <div class="kop">
        <?php if (!empty($company_logo_url)): ?>
            <img src="<?= esc($company_logo_url) ?>" alt="Logo" class="kop-logo">
        <?php endif; ?>
        <p class="kop-nama"><?= esc($company_name) ?></p>
        <?php if (!empty($company_address)): ?>
            <p class="kop-alamat"><?= nl2br(esc($company_address)) ?></p>
        <?php endif; ?>
    </div>

    <p class="doc-title">Surat Izin Cuti</p>

    <div class="content">
        <p>Kepada Yth.<br>
        <strong>Pimpinan Perusahaan</strong><br>
        Di Tempat</p>

        <p>Dengan hormat,</p>

        <p class="indent">
            Yang bertanda tangan di bawah ini:
        </p>

        <table style="width: 100%; margin-left: 30px; margin-bottom: 15px;">
            <tr>
                <td style="width: 150px;">Nama</td>
                <td>: <strong><?= esc($participant['name']) ?></strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: <?= esc($participant['nik'] ?? '—') ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: Karyawan</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: <?= esc($participant['address'] ?? '—') ?></td>
            </tr>
        </table>

        <p class="indent">
            Dengan ini mengajukan permohonan izin cuti untuk melaksanakan ibadah umroh/haji ke Tanah Suci Mekkah dan Madinah dengan rincian sebagai berikut:
        </p>

        <table style="width: 100%; margin-left: 30px; margin-bottom: 15px;">
            <tr>
                <td style="width: 150px;">Paket Perjalanan</td>
                <td>: <strong><?= esc($participant['package_name']) ?></strong></td>
            </tr>
            <tr>
                <td>Tanggal Keberangkatan</td>
                <td>: <?= !empty($participant['package_departure_date']) ? date('d F Y', strtotime($participant['package_departure_date'])) : '—' ?></td>
            </tr>
            <tr>
                <td>Lama Cuti</td>
                <td>: <?= date('d F Y', $departure_date) ?> s/d <?= date('d F Y', $return_date) ?> (sekitar <?= $duration_days ?? 9 ?> hari)</td>
            </tr>
        </table>

        <p class="indent">
            Demikian surat permohonan izin cuti ini saya buat dengan sebenar-benarnya. Atas perhatian dan kebijaksanaannya, saya ucapkan terima kasih.
        </p>

        <div class="signature-block">
            <div class="signature-left">
                <p class="ttd-label">Hormat saya,</p>
                <p class="nama-pemilik"><?= esc($participant['name']) ?></p>
            </div>
            <div class="signature-right">
                <p class="ttd-label">Mengetahui,</p>
                <?php if (!empty($company_name)): ?>
                    <p class="ttd-company"><?= esc($company_name) ?></p>
                <?php endif; ?>
                <p class="nama-pemilik"><?= esc($nama_direktur) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="no-print">
    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" onclick="window.print()">
        <i class="bi bi-printer-fill me-2"></i> Cetak Surat
    </button>
    <a href="<?= base_url('owner/print-documents') ?>" class="btn btn-light rounded-pill px-4 fw-bold border ms-2">Kembali</a>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>
