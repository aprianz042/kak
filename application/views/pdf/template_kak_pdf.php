<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  /* ===== UKURAN KERTAS A4 ===== */
  /* @page dipakai DomPDF untuk set ukuran & margin kertas */
  @page {
    size: A4 portrait;
    margin: 3cm 2cm 2.5cm 3cm; /* atas kanan bawah kiri */
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: Arial, sans-serif;
    font-size: 11pt;
    color: #000;
    line-height: 1.4;

    /* Lebar konten A4 = 21cm - 2cm - 3cm = 16cm */
    width: 16cm;
    margin: 0 auto; /* tengah saat di browser */
    /* padding sengaja tidak dipakai — margin sudah diatur di @page */
  }

  /* ===== COVER PAGE ===== */
  /* Tinggi konten A4 = 29.7cm - 3cm (atas) - 2.5cm (bawah) = 24.2cm — sama dengan @page margin */
  .cover-page {
    height: 24.2cm;
    display: flex;
    flex-direction: column;
  }
  .cover-page .judul-wrapper {
    flex: 1;                  /* ambil sisa ruang setelah header-box */
    display: flex;
    flex-direction: column;
    justify-content: center;  /* judul vertikal di tengah sisa halaman */
    align-items: center;
    text-align: center;
  }

  /* PAGE BREAK — DomPDF dan browser sama-sama menghormati ini */
  .page-break {
    page-break-after: always;
    break-after: page;
  }

  /* ===== HEADER SURAT ===== */
  .header-box {
    border: 2px solid #000;
    padding: 10px 14px;
    margin-bottom: 16px;
  }
  .header-box .to-label {
    font-size: 10pt;
    margin-bottom: 4px;
  }
  .header-box .to-value {
    font-size: 10pt;
    font-weight: bold;
    margin-bottom: 8px;
  }
  .header-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10.5pt;
  }
  .header-table td {
    padding: 1px 4px;
    vertical-align: top;
  }
  .header-table td:first-child {
    width: 38%;
    white-space: nowrap;
  }
  .header-table td.sep {
    width: 2%;
  }

  /* ===== JUDUL ===== */
  .judul-wrapper {
    text-align: center;
    margin: 16px 0 12px 0;
  }
  .judul-wrapper h1 {
    font-size: 13pt;
    font-weight: bold;
    text-transform: uppercase;
    margin-bottom: 2px;
  }
  .judul-wrapper p {
    font-size: 11pt;
    font-weight: bold;
  }
  .divider {
    border-top: 2px solid #000;
    margin: 8px 0;
  }

  /* ===== SECTION — tabel transparan 2 kolom: huruf | isi ===== */
  .section {
    margin-bottom: 12px;
  }

  /* Tabel pembungkus seluruh section (huruf A. + konten) */
  .section-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11pt;
  }
  .section-table > tr > td,
  .section-table tbody > tr > td {
    border: none;
    padding: 0;
    vertical-align: top;
  }

  /* Kolom kiri: huruf sub bab "A." "B." dst */
  .sec-label {
    width: 1.6em;        /* cukup untuk "H." */
    white-space: nowrap;
    font-weight: bold;
    padding-right: 4px !important;
  }

  /* Kolom kanan: judul + isi */
  .sec-body {
    /* mengambil sisa lebar halaman */
  }
  .sec-title {
    font-weight: bold;
    font-size: 11pt;
    margin-bottom: 4px;
  }
  .sec-content {
    font-size: 11pt;
    text-align: justify;
    margin-bottom: 4px;
  }

  /* ===== LIST di dalam section — tabel transparan 2 kolom: nomor | teks ===== */
  .list-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2px;
    font-size: 11pt;
  }
  .list-table td {
    border: none;
    padding: 1px 0;
    vertical-align: top;
  }
  .list-table .list-no {
    width: 2em;          /* cukup untuk "10." */
    white-space: nowrap;
  }
  .list-table .list-text {
    text-align: justify;
  }

  /* ===== TABEL ANGGARAN — border tetap tampil, tidak transparan ===== */
  .tabel-anggaran {
    width: 100%;
    border-collapse: collapse;
    font-size: 10.5pt;
    margin-top: 6px;
  }
  .tabel-anggaran th {
    background-color: #d9d9d9;
    border: 1px solid #000 !important;
    padding: 4px 6px;
    text-align: center;
    font-weight: bold;
  }
  .tabel-anggaran td {
    border: 1px solid #000 !important; /* override border:none dari section-table */
    padding: 4px 6px;
    vertical-align: top;
  }
  .tabel-anggaran td.text-center {
    text-align: center;
  }
  .tabel-anggaran td.text-right {
    text-align: right;
  }
  .tabel-anggaran tr.total-row td {
    font-weight: bold;
  }

  /* ===== TTD / TANDA TANGAN ===== */
  .ttd-wrapper {
    width: 100%;
    margin-top: 20px;
  }
  .ttd-table {
    width: 100%;
    border-collapse: collapse;
  }
  .ttd-table td {
    width: 50%;
    text-align: center;
    vertical-align: top;
    font-size: 11pt;
    padding: 0 10px;
  }
  .ttd-table .jabatan {
    font-size: 10.5pt;
    margin-bottom: 50px; /* ruang tanda tangan */
  }
  .ttd-table .nama {
    font-weight: bold;
    text-decoration: underline;
  }
  .ttd-table .nip {
    font-size: 10.5pt;
  }
</style>
</head>
<body>

<!-- ===== HALAMAN 1: COVER ===== -->
<div class="cover-page">

  <!-- HEADER SURAT -->
  <div class="header-box">
    <div class="to-label">Ditujukan Kepada KPA:</div>
    <div class="to-value">BPS Provinsi Kalimantan Barat<br>Di Badan Pusat Statistik Provinsi Kalimantan Barat</div>

    <table class="header-table">
      <tr>
        <td>Unit Organisasi</td>
        <td class="sep">:</td>
        <td><?= htmlspecialchars($unit_organisasi) ?></td>
      </tr>
      <tr>
        <td>Program</td>
        <td class="sep">:</td>
        <td><?= htmlspecialchars($program) ?></td>
      </tr>
      <tr>
        <td>Kegiatan</td>
        <td class="sep">:</td>
        <td><?= htmlspecialchars($kegiatan) ?></td>
      </tr>
      <tr>
        <td>KRO</td>
        <td class="sep">:</td>
        <td><?= htmlspecialchars($kro) ?></td>
      </tr>
      <tr>
        <td>RO</td>
        <td class="sep">:</td>
        <td><?= htmlspecialchars($ro) ?></td>
      </tr>
      <tr>
        <td>Komponen</td>
        <td class="sep">:</td>
        <td><?= htmlspecialchars($komponen) ?></td>
      </tr>
      <tr>
        <td>Item Kegiatan</td>
        <td class="sep">:</td>
        <td>(<?= htmlspecialchars($kode_anggaran) ?>) <?= htmlspecialchars($akun_anggaran) ?></td>
      </tr>
      <tr>
        <td>Lokasi Kegiatan</td>
        <td class="sep">:</td>
        <td><?= htmlspecialchars($kota_kegiatan) ?></td>
      </tr>
      <tr>
        <td>Tahun Anggaran</td>
        <td class="sep">:</td>
        <td><?= htmlspecialchars($tahun_anggaran) ?></td>
      </tr>
    </table>
  </div>

  <!-- JUDUL — di tengah secara vertikal di sisa halaman -->
  <div class="judul-wrapper">
    <h1>Kerangka Acuan Kerja (KAK)</h1>
    <p><?= htmlspecialchars($nama_kegiatan) ?></p>
    <p>BPS Provinsi Kalimantan Barat</p>
    <p>Tahun Anggaran <?= htmlspecialchars($tahun_anggaran) ?></p>
  </div>

</div><!-- end .cover-page -->

<!-- PAGE BREAK — isi dokumen mulai dari halaman 2 -->
<div class="page-break"></div>

<div class="divider"></div>

<!-- ================================================================
     HALAMAN 2+: ISI DOKUMEN
     Semua section pakai konsep tabel transparan 2 kolom:
     kolom kiri  = label/nomor (lebar fixed)
     kolom kanan = isi/konten
     Sehingga seluruh teks isi selalu rata sejajar satu sama lain.
================================================================ -->

<!-- ===== A. DASAR HUKUM ===== -->
<div class="section">
  <table class="section-table">
    <tr>
      <td class="sec-label">A.</td>
      <td class="sec-body">
        <div class="sec-title">Dasar Hukum</div>
        <div class="sec-content">
          Dasar hukum yang digunakan dalam kegiatan Tahun <?= htmlspecialchars($tahun_anggaran) ?> adalah:
        </div>
        <?php if (count($dashum) === 1): ?>
          <div class="sec-content"><?= htmlspecialchars($dashum[0]) ?></div>
        <?php elseif (count($dashum) > 1): ?>
          <table class="list-table">
            <?php foreach ($dashum as $i => $item): ?>
              <tr>
                <td class="list-no"><?= ($i + 1) ?>.</td>
                <td class="list-text"><?= htmlspecialchars($item) ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php else: ?>
          <div class="sec-content">-</div>
        <?php endif; ?>
      </td>
    </tr>
  </table>
</div>

<!-- ===== B. GAMBARAN UMUM ===== -->
<div class="section">
  <table class="section-table">
    <tr>
      <td class="sec-label">B.</td>
      <td class="sec-body">
        <div class="sec-title">Gambaran Umum</div>
        <div class="sec-content"><?= nl2br(htmlspecialchars($gambaran_umum ?: '-')) ?></div>
      </td>
    </tr>
  </table>
</div>

<!-- ===== C. MAKSUD DAN TUJUAN ===== -->
<div class="section">
  <table class="section-table">
    <tr>
      <td class="sec-label">C.</td>
      <td class="sec-body">
        <div class="sec-title">Maksud dan Tujuan Kegiatan</div>
        <?php if (count($maksud_tujuanArr) === 1): ?>
          <div class="sec-content"><?= htmlspecialchars($maksud_tujuanArr[0]) ?></div>
        <?php elseif (count($maksud_tujuanArr) > 1): ?>
          <table class="list-table">
            <?php foreach ($maksud_tujuanArr as $i => $item): ?>
              <tr>
                <td class="list-no"><?= ($i + 1) ?>.</td>
                <td class="list-text"><?= htmlspecialchars($item) ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php else: ?>
          <div class="sec-content">-</div>
        <?php endif; ?>
      </td>
    </tr>
  </table>
</div>

<!-- ===== D. KELUARAN ===== -->
<div class="section">
  <table class="section-table">
    <tr>
      <td class="sec-label">D.</td>
      <td class="sec-body">
        <div class="sec-title">Keluaran/Output</div>
        <?php if (count($keluaranArr) === 1): ?>
          <div class="sec-content"><?= htmlspecialchars($keluaranArr[0]) ?></div>
        <?php elseif (count($keluaranArr) > 1): ?>
          <table class="list-table">
            <?php foreach ($keluaranArr as $i => $item): ?>
              <tr>
                <td class="list-no"><?= ($i + 1) ?>.</td>
                <td class="list-text"><?= htmlspecialchars($item) ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php else: ?>
          <div class="sec-content">-</div>
        <?php endif; ?>
      </td>
    </tr>
  </table>
</div>

<!-- ===== E. ORGANISASI PELAKSANA ===== -->
<div class="section">
  <table class="section-table">
    <tr>
      <td class="sec-label">E.</td>
      <td class="sec-body">
        <div class="sec-title">Organisasi yang Melaksanakan Kegiatan</div>
        <div class="sec-content">
          Satker yang melaksanakan kegiatan adalah Badan Pusat Statistik Provinsi Kalimantan Barat.
        </div>
      </td>
    </tr>
  </table>
</div>

<!-- ===== F. WAKTU & TEMPAT ===== -->
<div class="section">
  <table class="section-table">
    <tr>
      <td class="sec-label">F.</td>
      <td class="sec-body">
        <div class="sec-title">Waktu dan Tempat Pelaksanaan Kegiatan</div>
        <div class="sec-content">
          Waktu Pembayaran <?= htmlspecialchars($nama_kegiatan) ?> dilaksanakan mulai Bulan <?= htmlspecialchars($waktu) ?>,
          paling lambat tanggal <?= htmlspecialchars($tanggal_bayar) ?> setiap bulannya dan tempat pelaksanaannya di
          <?= htmlspecialchars($lokasi) ?>, <?= htmlspecialchars($kota_kegiatan) ?>, <?= htmlspecialchars($provinsi) ?>.
        </div>
      </td>
    </tr>
  </table>
</div>

<!-- ===== G. SUMBER DANA & BIAYA ===== -->
<div class="section">
  <table class="section-table">
    <tr>
      <td class="sec-label">G.</td>
      <td class="sec-body">
        <div class="sec-title">Sumber Dana dan Perkiraan Biaya</div>
        <div class="sec-content">
          Total perkiraan biaya yang diperlukan untuk <?= htmlspecialchars($nama_kegiatan) ?>
          sebesar <strong>Rp. <?= htmlspecialchars($total_biaya_fmt) ?>,-</strong>
          (<?= htmlspecialchars($terbilang_total) ?>)/tahun yang akan dibebankan pada DIPA BPS Provinsi
          dengan rincian anggaran biaya sebagai berikut:
        </div>
        <table class="tabel-anggaran">
          <thead>
            <tr>
              <th>PROGRAM/KEGIATAN/OUTPUT/KOMPONEN/AKUN/DETIL BPS Provinsi</th>
              <th>Vol</th>
              <th>Satuan</th>
              <th>Jumlah Biaya</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= htmlspecialchars($kode_anggaran) ?> <?= htmlspecialchars($akun_anggaran) ?></td>
              <td class="text-center"><?= htmlspecialchars($vol) ?></td>
              <td class="text-center"><?= htmlspecialchars($satuan) ?></td>
              <td class="text-right">Rp. <?= htmlspecialchars($biaya_fmt) ?></td>
            </tr>
            <tr class="total-row">
              <td colspan="3" class="text-right">Total</td>
              <td class="text-right">Rp. <?= htmlspecialchars($total_biaya_fmt) ?></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>
</div>

<!-- ===== H. PENUTUP ===== -->
<div class="section">
  <table class="section-table">
    <tr>
      <td class="sec-label">H.</td>
      <td class="sec-body">
        <div class="sec-title">Penutup</div>
        <div class="sec-content">
          Apabila terdapat hal-hal yang bertentangan dengan ketentuan, peraturan, pedoman, dan kebijaksanaan
          pemerintah yang berlaku, maka segala yang termaktub di dalam Kerangka Acuan Kegiatan (KAK) akan
          diteliti kembali. Hal-hal yang belum diatur dalam KAK akan ditetapkan lebih lanjut.
          Demikian KAK ini dibuat untuk dipergunakan semestinya.
        </div>
      </td>
    </tr>
  </table>
</div>

<!-- ===== TANDA TANGAN ===== -->
<div class="ttd-wrapper">
  <table class="ttd-table">
    <tr>
      <td>
        <div>Mengetahui</div>
        <div class="jabatan">
          Pejabat Pembuat Komitmen<br>
          BPS Provinsi Kalimantan Barat
        </div>
        <div class="nama"><?= htmlspecialchars($nama_ppk) ?></div>
        <div class="nip">NIP. <?= htmlspecialchars($nip_ppk) ?></div>
      </td>
      <td>
        <div><?= htmlspecialchars($kota_kegiatan) ?>, <?= htmlspecialchars($tanggal_buat) ?></div>
        <div class="jabatan">
          Kepala Bagian Umum<br>
          BPS Provinsi Kalimantan Barat
        </div>
        <div class="nama"><?= htmlspecialchars($nama_kepala) ?></div>
        <div class="nip">NIP. <?= htmlspecialchars($nip_kepala) ?></div>
      </td>
    </tr>
  </table>
</div>

</body>
</html>