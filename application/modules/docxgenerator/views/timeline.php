<div class="row">
    <div class="col-md-6 col-12 scroll-col">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5 mb-0">Timeline Dokumen</h3>
        </div>

        <div id="message" class="mt-3 d-none"></div>
        <div id="tl_alert" class="alert d-none mt-2"></div>

        <?php 
        $badge = [
            'draft'         => 'secondary',
            'ajuan_baru'    => 'primary',
            'revisi'        => 'warning',
            'ajuan_revisi'  => 'warning',
            'disetujui'     => 'success'
        ];
        $lastIndex = count($timeline) - 1;
        ?>

        <?php foreach ($timeline as $i => $d): ?>
            <?php
            $justifyClass = ($i % 2 == 0) ? 'justify-content-start' : 'justify-content-end';
            $textAlign    = ($i % 2 == 0) ? 'text-start' : 'text-end';
            ?>
            <div class="d-flex <?= $justifyClass ?>">
                <div class="mb-3 p-3 border rounded bg-light w-100 <?= $textAlign ?>">
                    <div>
                        <span class="badge bg-<?= $badge[$d->status] ?? 'secondary' ?>">
                            <?= $d->status ?>
                        </span>
                    </div>
                    <div class="mt-1">
                        <small><?= $d->created_at ?></small>
                    </div>
                    <div class="mt-2">
                        <strong><?= $d->pengirim ?></strong> ➝ 
                        <strong><?= $d->penerima ?></strong>
                    </div>
                    <div class="mt-2 text-muted">
                        <?= $d->pesan ?>
                    </div>
                </div>
            </div>

            <?php 
            $role = $this->session->userdata('role');
            if ($role == 'operator' && $i === $lastIndex && $d->status === 'revisi'): ?>
                <div class="mt-3">
                    <button class="btn btn-sm btn-success w-100" data-bs-toggle="modal" data-bs-target="#revisiModal<?= $d->id_dokumen ?>">
                        <i class="fa fa-edit"></i> Revisi
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($d->status === 'disetujui'): ?>
                <div class="mt-3">
                    <a class="btn btn-sm btn-primary w-100" href="<?= base_url('docxgenerator/download_pdf/'.$d->file_doc) ?>">
                        <i class="fa fa-download"></i> Download Dokumen
                    </a>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>

        <?php 
        $role = $this->session->userdata('role');
        $lastTimeline = $timeline[$lastIndex] ?? null;
        $showFormTL = ($role == 'ppk' && $lastTimeline && in_array($lastTimeline->status, ['ajuan_baru', 'ajuan_revisi']));
        ?>

        <?php if ($showFormTL): ?>
            <!-- <form id="formTL">
                <input type="hidden" name="id_dokumen" value="<?= $id_dokumen ?>" id="tl_id">
                <div class="mb-3">
                    <label>Tindak Lanjut</label>
                    <select name="aksi" id="tl_status" class="form-control" required>
                        <option value="">-- pilih --</option>
                        <option value="revisi">Revisi</option>
                        <option value="disetujui">ACC</option>
                    </select>
                </div>
                <div class="mb-3 d-none" id="wrap_pesan">
                    <label>Pesan Revisi</label>
                    <textarea name="pesan" id="tl_pesan" class="form-control"></textarea>
                </div>
                <button class="btn btn-primary w-100">Kirim</button>
            </form> -->

            <form method="post" action="<?= base_url('docxgenerator/tindak_lanjut_ppk') ?>">
                <input type="hidden" name="id_dokumen" value="<?= $id_dokumen ?>">

                <div class="mb-3">
                    <label>Tindak Lanjut</label>
                    <select name="aksi" id="tl_status" class="form-control" required>
                        <option value="">-- pilih --</option>
                        <option value="revisi">Revisi</option>
                        <option value="disetujui">ACC</option>
                    </select>
                </div>

                <div class="mb-3 d-none" id="wrap_pesan">
                    <label>Pesan Revisi</label>
                    <textarea name="pesan" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Kirim</button>
            </form>
        <?php endif; ?>
    </div>

    <?php
    function format_text_list($text) {
        $text = trim($text ?? '');
        if (!$text) return '-';
        if (strpos($text, "\n") !== false) {
            $lines = array_filter(array_map('trim', explode("\n", $text)));
            $html = '<ol class="mb-0">';
            foreach ($lines as $line) {
                $html .= '<li>' . htmlspecialchars($line) . '</li>';
            }
            $html .= '</ol>';
            return $html;
        }
        return htmlspecialchars($text);
    }

    function terbilang($angka) {
        $angka = abs($angka);
        $kata  = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
        'sepuluh', 'sebelas'];
        $hasil = '';
        if ($angka < 12) {
            $hasil = $kata[$angka];
        } elseif ($angka < 20) {
            $hasil = terbilang($angka - 10) . ' belas';
        } elseif ($angka < 100) {
            $hasil = terbilang(intval($angka / 10)) . ' puluh ' . terbilang($angka % 10);
        } elseif ($angka < 200) {
            $hasil = 'seratus ' . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $hasil = terbilang(intval($angka / 100)) . ' ratus ' . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $hasil = 'seribu ' . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $hasil = terbilang(intval($angka / 1000)) . ' ribu ' . terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $hasil = terbilang(intval($angka / 1000000)) . ' juta ' . terbilang($angka % 1000000);
        } else {
            $hasil = terbilang(intval($angka / 1000000000)) . ' miliar ' . terbilang($angka % 1000000000);
        }
        return trim($hasil);
    }
    ?>

    <div class="col-md-6 col-12 scroll-col">
        <?php foreach ($documents as $doc): ?>

            <?php
            $unit_organisasi  = $doc->unit_organisasi ?? '-';
            $program          = $doc->program ?? '-';
            $kegiatan         = $doc->kegiatan ?? '-';
            $kro              = $doc->kro ?? '-';
            $ro               = $doc->ro ?? '-';
            $komponen         = $doc->komponen ?? '-';
            $kode_anggaran    = $doc->kode_anggaran ?? '-';
            $akun_anggaran    = $doc->akun_anggaran ?? '-';
            $kota_kegiatan    = $doc->kota_kegiatan ?? '-';
            $provinsi         = $doc->provinsi ?? '-';
            $tahun_anggaran   = $doc->tahun_anggaran ?? '-';
            $nama_kegiatan    = $doc->nama_kegiatan ?? '-';
            $waktu            = $doc->waktu ?? '-';
            $tanggal_bayar    = $doc->tanggal_bayar ?? '-';
            $lokasi           = $doc->lokasi ?? '-';
            $vol              = $doc->vol ?? '-';
            $satuan           = $doc->satuan ?? '-';
            $biaya_raw        = (int) preg_replace('/[^0-9]/', '', $doc->biaya ?? '0');
            $biaya_fmt        = number_format($biaya_raw, 0, ',', '.');
            $total_biaya_fmt  = $biaya_fmt; 
            $terbilang_total  = ucfirst(terbilang($biaya_raw)) . ' Rupiah';
            $nama_ppk         = $doc->nama_ppk ?? '-';
            $nip_ppk          = $doc->nip_ppk ?? '-';
            $nama_kepala      = isset($kepala) ? $kepala->nama : '-';
            $nip_kepala       = isset($kepala) ? $kepala->nip : '-';

            $bulan_indo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $date = new DateTime($doc->created_at);
            $tanggal_indo = $date->format('d') . ' ' . $bulan_indo[(int)$date->format('n')] . ' ' . $date->format('Y');

            $tanggal_buat     = $tanggal_indo;

            $dashum           = array_filter(array_map('trim', explode("\n", $doc->dasar_hukum ?? '')));
            $maksud_tujuanArr = array_filter(array_map('trim', explode("\n", $doc->maksud_tujuan ?? '')));
            $keluaranArr      = array_filter(array_map('trim', explode("\n", $doc->keluaran ?? '')));
            $gambaran_umum    = $doc->gambaran_umum ?? '';
            ?>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Preview Dokumen KAK</h5>
                </div>

                <div class="card-body p-0">

                    <div style="font-family: Arial, sans-serif; font-size: 11pt; color: #000; line-height: 1.4; padding: 1.5cm 1cm;">

                        <div style="text-align:center; font-weight:bold; margin-bottom:16px;">
                            <div style="font-size:10pt; font-weight:bold; margin-top:12px;">
                                Ditujukan Kepada KPA: BPS Provinsi Kalimantan Barat<br>
                                Di Badan Pusat Statistik Provinsi Kalimantan Barat
                            </div>
                            <table style="width:100%; border-collapse:collapse; font-size:10.5pt; margin-top:20px; text-align:left;">
                                <tr><td style="width:38%; padding:2px 4px;">Unit Organisasi</td><td style="width:2%;">:</td><td><?= htmlspecialchars($unit_organisasi) ?></td></tr>
                                <tr><td style="padding:2px 4px;">Program</td><td>:</td><td><?= htmlspecialchars($program) ?></td></tr>
                                <tr><td style="padding:2px 4px;">Kegiatan</td><td>:</td><td><?= htmlspecialchars($kegiatan) ?></td></tr>
                                <tr><td style="padding:2px 4px;">KRO</td><td>:</td><td><?= htmlspecialchars($kro) ?></td></tr>
                                <tr><td style="padding:2px 4px;">RO</td><td>:</td><td><?= htmlspecialchars($ro) ?></td></tr>
                                <tr><td style="padding:2px 4px;">Komponen</td><td>:</td><td><?= htmlspecialchars($komponen) ?></td></tr>
                                <tr><td style="padding:2px 4px;">Item Kegiatan</td><td>:</td><td>(<?= htmlspecialchars($kode_anggaran) ?>) <?= htmlspecialchars($akun_anggaran) ?></td></tr>
                                <tr><td style="padding:2px 4px;">Lokasi Kegiatan</td><td>:</td><td><?= htmlspecialchars($kota_kegiatan) ?></td></tr>
                            </table>
                        </div>

                        <div style="text-align:center; margin:20px 0 16px;">
                            <h1 style="font-size:13pt; font-weight:bold; text-transform:uppercase; margin:0;">
                                Tahun Anggaran <?= htmlspecialchars($tahun_anggaran) ?>
                            </h1>
                        </div>

                        <hr style="border-top:2px solid #000; margin:8px 0 16px;">

                        <div style="margin-bottom:12px;">
                            <table style="width:100%; border-collapse:collapse; font-size:11pt;">
                                <tr>
                                    <td style="width:1.6em; vertical-align:top; font-weight:bold; padding-right:4px;">A.</td>
                                    <td style="vertical-align:top;">
                                        <div style="font-weight:bold; margin-bottom:4px;">Dasar Hukum</div>
                                        <div>Dasar hukum yang digunakan dalam kegiatan Tahun <?= htmlspecialchars($tahun_anggaran) ?> adalah:</div>
                                        <?php if (count($dashum) === 1): ?>
                                            <div><?= htmlspecialchars(reset($dashum)) ?></div>
                                        <?php elseif (count($dashum) > 1): ?>
                                            <table style="width:100%; border-collapse:collapse; font-size:11pt; margin-top:2px;">
                                                <?php $j=1; foreach ($dashum as $item): ?>
                                                <tr>
                                                    <td style="width:2em; vertical-align:top;"><?= $j++ ?>.</td>
                                                    <td style="text-align:justify;"><?= htmlspecialchars($item) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    <?php else: ?>
                                        <div>-</div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style="margin-bottom:12px;">
                        <table style="width:100%; border-collapse:collapse; font-size:11pt;">
                            <tr>
                                <td style="width:1.6em; vertical-align:top; font-weight:bold; padding-right:4px;">B.</td>
                                <td style="vertical-align:top;">
                                    <div style="font-weight:bold; margin-bottom:4px;">Gambaran Umum</div>
                                    <div style="text-align:justify;"><?= nl2br(htmlspecialchars($gambaran_umum ?: '-')) ?></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style="margin-bottom:12px;">
                        <table style="width:100%; border-collapse:collapse; font-size:11pt;">
                            <tr>
                                <td style="width:1.6em; vertical-align:top; font-weight:bold; padding-right:4px;">C.</td>
                                <td style="vertical-align:top;">
                                    <div style="font-weight:bold; margin-bottom:4px;">Maksud dan Tujuan Kegiatan</div>
                                    <?php if (count($maksud_tujuanArr) === 1): ?>
                                        <div><?= htmlspecialchars(reset($maksud_tujuanArr)) ?></div>
                                    <?php elseif (count($maksud_tujuanArr) > 1): ?>
                                        <table style="width:100%; border-collapse:collapse; font-size:11pt; margin-top:2px;">
                                            <?php $j=1; foreach ($maksud_tujuanArr as $item): ?>
                                            <tr>
                                                <td style="width:2em; vertical-align:top;"><?= $j++ ?>.</td>
                                                <td style="text-align:justify;"><?= htmlspecialchars($item) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                <?php else: ?>
                                    <div>-</div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom:12px;">
                    <table style="width:100%; border-collapse:collapse; font-size:11pt;">
                        <tr>
                            <td style="width:1.6em; vertical-align:top; font-weight:bold; padding-right:4px;">D.</td>
                            <td style="vertical-align:top;">
                                <div style="font-weight:bold; margin-bottom:4px;">Keluaran/Output</div>
                                <?php if (count($keluaranArr) === 1): ?>
                                    <div><?= htmlspecialchars(reset($keluaranArr)) ?></div>
                                <?php elseif (count($keluaranArr) > 1): ?>
                                    <table style="width:100%; border-collapse:collapse; font-size:11pt; margin-top:2px;">
                                        <?php $j=1; foreach ($keluaranArr as $item): ?>
                                        <tr>
                                            <td style="width:2em; vertical-align:top;"><?= $j++ ?>.</td>
                                            <td style="text-align:justify;"><?= htmlspecialchars($item) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php else: ?>
                                <div>-</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-bottom:12px;">
                <table style="width:100%; border-collapse:collapse; font-size:11pt;">
                    <tr>
                        <td style="width:1.6em; vertical-align:top; font-weight:bold; padding-right:4px;">E.</td>
                        <td style="vertical-align:top;">
                            <div style="font-weight:bold; margin-bottom:4px;">Organisasi yang Melaksanakan Kegiatan</div>
                            <div>Satker yang melaksanakan kegiatan adalah Badan Pusat Statistik Provinsi Kalimantan Barat.</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-bottom:12px;">
                <table style="width:100%; border-collapse:collapse; font-size:11pt;">
                    <tr>
                        <td style="width:1.6em; vertical-align:top; font-weight:bold; padding-right:4px;">F.</td>
                        <td style="vertical-align:top;">
                            <div style="font-weight:bold; margin-bottom:4px;">Waktu dan Tempat Pelaksanaan Kegiatan</div>
                            <div style="text-align:justify;">
                                Waktu Pembayaran <?= htmlspecialchars($nama_kegiatan) ?> dilaksanakan mulai Bulan <?= htmlspecialchars($waktu) ?>,
                                paling lambat tanggal <?= htmlspecialchars($tanggal_bayar) ?> setiap bulannya dan tempat pelaksanaannya di
                                <?= htmlspecialchars($lokasi) ?>, <?= htmlspecialchars($kota_kegiatan) ?>, <?= htmlspecialchars($provinsi) ?>.
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-bottom:12px;">
                <table style="width:100%; border-collapse:collapse; font-size:11pt;">
                    <tr>
                        <td style="width:1.6em; vertical-align:top; font-weight:bold; padding-right:4px;">G.</td>
                        <td style="vertical-align:top;">
                            <div style="font-weight:bold; margin-bottom:4px;">Sumber Dana dan Perkiraan Biaya</div>
                            <div style="text-align:justify; margin-bottom:8px;">
                                Total perkiraan biaya yang diperlukan untuk <?= htmlspecialchars($nama_kegiatan) ?>
                                sebesar <strong>Rp. <?= htmlspecialchars($total_biaya_fmt) ?>,-</strong>
                                (<?= htmlspecialchars($terbilang_total) ?>)/tahun yang akan dibebankan pada DIPA BPS Provinsi
                                dengan rincian anggaran biaya sebagai berikut:
                            </div>
                            <table style="width:100%; border-collapse:collapse; font-size:10.5pt;">
                                <thead>
                                    <tr>
                                        <th style="background:#d9d9d9; border:1px solid #000; padding:6px 8px; text-align:center;">PROGRAM/KEGIATAN/OUTPUT/KOMPONEN/AKUN/DETIL BPS Provinsi</th>
                                        <th style="background:#d9d9d9; border:1px solid #000; padding:6px 8px; text-align:center;">Vol</th>
                                        <th style="background:#d9d9d9; border:1px solid #000; padding:6px 8px; text-align:center;">Satuan</th>
                                        <th style="background:#d9d9d9; border:1px solid #000; padding:6px 8px; text-align:center;">Jumlah Biaya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="border:1px solid #000; padding:6px 8px;"><?= htmlspecialchars($kode_anggaran) ?> <?= htmlspecialchars($akun_anggaran) ?></td>
                                        <td style="border:1px solid #000; padding:6px 8px; text-align:center;"><?= htmlspecialchars($vol) ?></td>
                                        <td style="border:1px solid #000; padding:6px 8px; text-align:center;"><?= htmlspecialchars($satuan) ?></td>
                                        <td style="border:1px solid #000; padding:6px 8px; text-align:right;">Rp. <?= htmlspecialchars($biaya_fmt) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="border:1px solid #000; padding:6px 8px; text-align:right; font-weight:bold;">Total</td>
                                        <td style="border:1px solid #000; padding:6px 8px; text-align:right; font-weight:bold;">Rp. <?= htmlspecialchars($total_biaya_fmt) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-bottom:12px;">
                <table style="width:100%; border-collapse:collapse; font-size:11pt;">
                    <tr>
                        <td style="width:1.6em; vertical-align:top; font-weight:bold; padding-right:4px;">H.</td>
                        <td style="vertical-align:top;">
                            <div style="font-weight:bold; margin-bottom:4px;">Penutup</div>
                            <div style="text-align:justify;">
                                Apabila terdapat hal-hal yang bertentangan dengan ketentuan, peraturan, pedoman, dan kebijaksanaan
                                pemerintah yang berlaku, maka segala yang termaktub di dalam Kerangka Acuan Kegiatan (KAK) akan
                                diteliti kembali. Hal-hal yang belum diatur dalam KAK akan ditetapkan lebih lanjut.
                                Demikian KAK ini dibuat untuk dipergunakan semestinya.
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-top:20px;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:50%; text-align:center; vertical-align:top; font-size:11pt; padding:0 10px;">
                            <div>Mengetahui</div>
                            <div style="margin-bottom:50px;">
                                Pejabat Pembuat Komitmen<br>
                                BPS Provinsi Kalimantan Barat
                            </div>
                            <div style="font-weight:bold; text-decoration:underline;"><?= htmlspecialchars($nama_ppk) ?></div>
                            <div style="font-size:10.5pt;">NIP. <?= htmlspecialchars($nip_ppk) ?></div>
                        </td>
                        <td style="width:50%; text-align:center; vertical-align:top; font-size:11pt; padding:0 10px;">
                            <div>Pontianak, <?= htmlspecialchars($tanggal_buat) ?></div>
                            <div style="margin-bottom:50px;">
                                Kepala Bagian Umum<br>
                                BPS Provinsi Kalimantan Barat
                            </div>
                            <div style="font-weight:bold; text-decoration:underline;"><?= htmlspecialchars($nama_kepala) ?></div>
                            <div style="font-size:10.5pt;">NIP. <?= htmlspecialchars($nip_kepala) ?></div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

<?php endforeach; ?>
</div>
</div>


<?php foreach ($documents as $doc): ?>

    <div class="modal fade" id="revisiModal<?= $doc->id ?>" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Revisi Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form method="post" action="<?= base_url('docxgenerator/revisi') ?>" class="needs-validation" novalidate>

                        <input type="hidden" name="id_dokumen" value="<?= $doc->id ?>">

                        <div class="mb-3">
                            <label class="form-label">Unit Organisasi</label>
                            <input type="text" name="unit_organisasi" class="form-control" value="<?= $doc->unit_organisasi ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Program</label>
                            <input type="text" name="program" class="form-control" value="<?= $doc->program ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kegiatan</label>
                            <input type="text" name="kegiatan" class="form-control" value="<?= $doc->kegiatan ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">KRO</label>
                            <input type="text" name="kro" class="form-control" value="<?= $doc->kro ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">RO</label>
                            <input type="text" name="ro" class="form-control" value="<?= $doc->ro ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Komponen</label>
                            <input type="text" name="komponen" class="form-control" value="<?= $doc->komponen ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Anggaran</label>
                            <select name="kode_anggaran" class="form-control" required>
                                <option value="">-- Pilih Anggaran --</option>
                                <?php foreach ($anggaran as $row): ?>
                                    <option value="<?= $row->kode_akun ?>" 
                                        <?= ($doc->kode_anggaran ?? '') == $row->kode_akun ? 'selected' : '' ?>>
                                        <?= $row->kode_akun ?> - <?= $row->nama_kegiatan ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <input type="hidden" name="akun_anggaran" id="akun_anggaran_<?= $doc->id ?>" value="<?= $doc->akun_anggaran ?>">

                        <div class="mb-3">
                            <label class="form-label">Kab/Kota</label>
                            <select id="regency_id_<?= $doc->id ?>" name="regency_id" class="form-control" required></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" id="provinsi_<?= $doc->id ?>" name="provinsi" class="form-control" value="<?= $doc->provinsi ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tahun Anggaran</label>
                            <input type="text" name="tahun_anggaran" class="form-control" value="<?= $doc->tahun_anggaran ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dasar Hukum</label>
                            <textarea name="dasar_hukum" class="form-control"><?= $doc->dasar_hukum ?? '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambaran Umum</label>
                            <textarea name="gambaran_umum" class="form-control"><?= $doc->gambaran_umum ?? '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Maksud dan Tujuan</label>
                            <textarea name="maksud_tujuan" class="form-control"><?= $doc->maksud_tujuan ?? '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keluaran</label>
                            <textarea name="keluaran" class="form-control"><?= $doc->keluaran ?? '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control" value="<?= $doc->nama_kegiatan ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="text" name="waktu" class="form-control" value="<?= $doc->waktu ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="text" name="tanggal_bayar" class="form-control" value="<?= $doc->tanggal_bayar ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" value="<?= $doc->lokasi ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vol</label>
                            <input type="text" name="vol" class="form-control" value="<?= $doc->vol ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Satuan</label>
                            <input type="text" name="satuan" class="form-control" value="<?= $doc->satuan ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Biaya</label>
                            <input type="text" name="biaya" class="form-control" value="<?= $doc->biaya ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">PPK</label>
                            <input type="text" name="nama_ppk" class="form-control" value="<?= $doc->nama_ppk ?? '' ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIP PPK</label>
                            <input type="text" name="nip_ppk" class="form-control" value="<?= $doc->nip_ppk ?? '' ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kepala</label>
                            <input type="text" class="form-control" value="<?= isset($kepala) ? $kepala->nama : '' ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIP Kepala</label>
                            <input type="text" class="form-control" value="<?= isset($kepala) ? $kepala->nip : '' ?>" disabled>
                        </div>

                        <button type="submit" class="btn btn-warning">Simpan Revisi</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>



<script>
    document.addEventListener('DOMContentLoaded', function () {

/* ================= LOAD LOG ================= */
        function loadLog(id) {
            const container = document.getElementById('logContainer');
            container.innerHTML = 'Loading...';

            fetch("<?= base_url('docxgenerator/get_log/') ?>" + id)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if (!data.length) {
                    html = '<p class="text-muted">Belum ada log</p>';
                } else {
                    data.forEach(item => {
                        let badge = {
                            draft:'secondary',
                            ajuan_baru:'primary',
                            revisi:'warning',
                            disetujui:'success'
                        }[item.status] || 'dark';

                        html += `
                        <div class="mb-3 p-3 border rounded bg-light">
                        <div class="d-flex justify-content-between">
                        <span class="badge bg-${badge}">${item.status}</span>
                        <small>${item.created_at}</small>
                        </div>
                        <div class="mt-2">
                        <strong>${item.pengirim}</strong> ➝ 
                        <strong>${item.penerima || '-'}</strong>
                        </div>
                        <div class="mt-2 text-muted">
                            ${item.pesan ? item.pesan : '-'}
                        </div>
                        </div>`;
                    });
                }
                container.innerHTML = html;
            });
        }

/* ================= KODE ANGGARAN ================= */
        document.querySelectorAll('select[name="kode_anggaran"]').forEach(function(select){
            select.addEventListener('change', function(){
                const selected = this.options[this.selectedIndex];
                const text = selected.text;
                let akun = text.split(' - ')[1] || '';
                const hidden = this.closest('form').querySelector('[name="akun_anggaran"]');
                if (hidden) hidden.value = akun.trim();
            });
        });

/* ================= CLICK BUTTON ================= */
        document.querySelectorAll('.btn-log').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const tlId     = document.getElementById('tl_id');
                const formTL   = document.getElementById('formTL');
                const wrapPesan = document.getElementById('wrap_pesan');

                if (tlId) tlId.value = id;
                if (formTL) formTL.reset();
                if (wrapPesan) wrapPesan.classList.add('d-none');

                loadLog(id);
            });
        });

/* ================= STATUS CHANGE ================= */
        const tlStatus = document.getElementById('tl_status');
        if (tlStatus) {
            tlStatus.addEventListener('change', function(){
                const wrap = document.getElementById('wrap_pesan');
                if (this.value === 'revisi') {
                    wrap.classList.remove('d-none');
                } else {
                    wrap.classList.add('d-none');
                }
            });
        }

/* ================= SUBMIT ================= */
        const formTL = document.getElementById('formTL');
        if (formTL) {
            formTL.addEventListener('submit', function(e){
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const alertBox = document.getElementById('tl_alert');

                function showAlert(type, message){
                    if (alertBox) {
                        alertBox.classList.remove('d-none','alert-success','alert-danger');
                        alertBox.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
                        alertBox.innerText = message;
                    } else {
                        alert(message);
                    }
                }

                fetch("<?= base_url('docxgenerator/tindak_lanjut_ppk') ?>", {
                    method: 'POST',
                    body: formData
                })
                .then(res => {
                    return res.text().then(text => {
                        console.log('RAW RESPONSE:', text);
                        try {
                            return JSON.parse(text);
                        } catch(e) {
                            throw new Error('Response bukan JSON: ' + text);
                        }
                    });
                })
                .then(res => {
                    if (res.status) {
                        showAlert('success', 'Berhasil diproses');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        showAlert('error', res.message || 'Gagal diproses');
                    }
                })
                .catch(err => {
                    console.error('ERROR:', err.message);
                    showAlert('error', err.message);
                });
            });
        }

/* ================= SELECT2 REVISI MODAL ================= */
        <?php foreach ($documents as $doc): ?>
            (function () {
                const id          = "<?= $doc->id ?>";
                const regencyId   = "<?= $doc->regency_id ?? '' ?>";
                const regencyText = "<?= addslashes($doc->kota_kegiatan ?? '') ?>";
                const province    = "<?= addslashes($doc->provinsi ?? '') ?>";
                const provinceId  = "<?= $doc->province_id ?? '' ?>";

                let select2Ready = false;

                document.getElementById('revisiModal' + id).addEventListener('shown.bs.modal', function () {

                    const $regency = $('#regency_id_' + id);

                    if (!select2Ready) {
                        select2Ready = true;

                        $regency.select2({
                            dropdownParent: document.getElementById('revisiModal' + id), 
                            placeholder: 'Ketik nama Kab/Kota...',
                            minimumInputLength: 2,
                            width: '100%',
                            allowClear: true,
                            ajax: {
                                url: "<?= base_url('docxgenerator/search_regencies') ?>",
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    return { q: params.term };
                                },
                                processResults: function (data) {
                                    return { results: data };
                                },
                                cache: true
                            }
                        });

                        $regency.on('select2:select', function (e) {
                            const d = e.params.data;
                            $('#provinsi_' + id).val(d.province || '');
                            $('#province_id_' + id).val(d.province_id || '');
                        });

                        $regency.on('select2:clear', function () {
                            $('#provinsi_' + id).val('');
                            $('#province_id_' + id).val('');
                        });
                    }

                    if (regencyText) {
                        $regency.find('option').remove();
                        const val = regencyId ? regencyId : regencyText;
                        const option = new Option(regencyText, val, true, true);
                        $regency.append(option).trigger('change');
                        $('#provinsi_' + id).val(province);
                        $('#province_id_' + id).val(provinceId);
                    }
                });
            })();
        <?php endforeach; ?>

    });
</script>
