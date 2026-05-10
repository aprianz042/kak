<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="h5 mb-0">Daftar Dokumen</h3>
    <button type="button" class="btn btn-primary" onclick="openTambahDokumenModal()">
        Tambah Dokumen
    </button>
</div>

<div id="message" class="mt-3 d-none"></div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Unit Organisasi</th>
            <th>Nama Kegiatan</th>
            <th>Tahun</th>
            <th>Kota Kegiatan</th>
            <th>Provinsi</th>
            <th>Kode Anggaran</th>
            <th>Akun</th>
            <th>PPK</th>
            <!-- <th>Aksi</th> -->
            <th>Pengajuan</th>
            <th>Status Dokumen</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($documents as $i => $d): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($d->unit_organisasi) ?></td>
                <td><?= htmlspecialchars($d->nama_kegiatan) ?></td>
                <td><?= htmlspecialchars($d->tahun_anggaran) ?></td>
                <td><?= htmlspecialchars($d->kota_kegiatan) ?></td>
                <td><?= htmlspecialchars($d->provinsi) ?></td>
                <td><?= htmlspecialchars($d->kode_anggaran) ?></td>
                <td><?= htmlspecialchars($d->akun_anggaran) ?></td>
                <td><?= htmlspecialchars($d->nama_ppk) ?></td>

                <!-- <td>
                    <button class="btn btn-sm btn-danger btn-delete"
                        data-id="<?= $d->id ?>"
                        data-nama="<?= htmlspecialchars($d->nama_kegiatan) ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal">
                        Hapus
                    </button>
                </td> -->

                <td>
                    <a class="btn btn-sm btn-primary" href="<?= base_url('docxgenerator/timeline_dok/'.$d->id) ?>">Riwayat Dok</a>
                </td>

                <td>
                    <?php if ($d->status === 'draft'): ?>
                        <a class="btn btn-sm btn-success w-100 mb-2" href="<?= base_url('docxgenerator/pengajuan/'.$d->id) ?>">Ajukan Draft</a>
                        <button class="btn btn-sm btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#revisiModal<?= $d->id ?>">
                            <i class="fa fa-edit"></i> Edit Draft
                        </button>

                        <button class="btn btn-sm btn-danger w-100 btn-delete" data-id="<?= $d->id ?>" data-nama="<?= htmlspecialchars($d->nama_kegiatan) ?>" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i> Hapus Draft</button>

                    <?php elseif ($d->status === 'disetujui'): ?>
                        <a class="btn btn-sm btn-success w-100" href="<?= base_url('docxgenerator/download_pdf/'.$d->file_doc) ?>">Download Dokumen</a>

                    <?php else: ?>
                        <span><?= htmlspecialchars($d->status) ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- ===================== MODAL DOCX VIEWER ===================== -->
<div class="modal fade" id="docxModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="docxViewer" style="width:100%; height:80vh;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================= -->

<!-- ===================== MODAL DELETE ===================== -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus dokumen berikut?</p>
                <p class="text-danger" id="deleteDocName"></p>
            </div>
            <div class="modal-footer">
                <form method="post" action="<?= base_url('docxgenerator/delete') ?>">
                    <input type="hidden" name="id" id="deleteDocId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ========================================================= -->

<!-- ===================== MODAL TAMBAH DOKUMEN ===================== -->
<div class="modal fade" id="tambahDokumenModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= base_url('docxgenerator/generate') ?>" class="needs-validation" novalidate>

                    <div class="mb-3">
                        <label class="form-label">Unit Organisasi <span class="text-danger">*</span></label>
                        <input type="text" name="unit_organisasi" class="form-control" placeholder="Masukkan Unit Organisasi" required>
                        <div class="invalid-feedback">Unit Organisasi wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Program <span class="text-danger">*</span></label>
                        <input type="text" name="program" class="form-control" placeholder="Masukkan Program" required>
                        <div class="invalid-feedback">Program wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="kegiatan" class="form-control" placeholder="Masukkan Kegiatan" required>
                        <div class="invalid-feedback">Kegiatan wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">KRO <span class="text-danger">*</span></label>
                        <input type="text" name="kro" class="form-control" placeholder="Masukkan KRO" required>
                        <div class="invalid-feedback">KRO wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">RO <span class="text-danger">*</span></label>
                        <input type="text" name="ro" class="form-control" placeholder="Masukkan RO" required>
                        <div class="invalid-feedback">RO wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Komponen <span class="text-danger">*</span></label>
                        <input type="text" name="komponen" class="form-control" placeholder="Masukkan Komponen" required>
                        <div class="invalid-feedback">Komponen wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Anggaran <span class="text-danger">*</span></label>
                        <select name="anggaran_id" id="anggaran_id" class="form-control" required>
                            <option value="">-- Pilih Anggaran --</option>
                            <?php foreach ($anggaran as $row): ?>
                                <option value="<?= $row->id ?>" data-kode="<?= $row->kode_akun ?>" data-nama="<?= $row->nama_kegiatan ?>">
                                    <?= $row->kode_akun ?> - <?= $row->nama_kegiatan ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Anggaran wajib dipilih.</div>
                    </div>

                    <input type="hidden" name="kode_anggaran" id="kode_anggaran">
                    <input type="hidden" name="akun_anggaran" id="akun_anggaran">

                    <div class="mb-3">
                        <label class="form-label">Kab/Kota Kegiatan <span class="text-danger">*</span></label>
                        <select name="regency_id" id="regency_id" class="form-control" required></select>
                        <div class="invalid-feedback">Kab/Kota wajib dipilih.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                        <input type="text" name="provinsi" id="provinsi" class="form-control" placeholder="Terisi otomatis dari Kab/Kota" readonly required>
                        <input type="hidden" name="province_id" id="province_id">
                        <div class="invalid-feedback">Provinsi wajib terisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tahun Anggaran <span class="text-danger">*</span></label>
                        <input type="text" name="tahun_anggaran" class="form-control" placeholder="Masukkan Tahun Anggaran" required>
                        <div class="invalid-feedback">Tahun Anggaran wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dasar Hukum <span class="text-danger">*</span></label>
                        <textarea name="dasar_hukum" class="form-control" placeholder="Jangan masukkan nomor urut, cukup pisahkan dengan ENTER"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambaran Umum</label>
                        <textarea name="gambaran_umum" class="form-control" placeholder="Masukkan Gambaran Umum"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Maksud dan Tujuan</label>
                        <textarea name="maksud_tujuan" class="form-control" placeholder="Masukkan Maksud dan Tujuan"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keluaran/Output</label>
                        <textarea name="keluaran" class="form-control" placeholder="Masukkan Keluaran/Output"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" placeholder="Masukkan Nama Kegiatan">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Waktu</label>
                        <input type="text" name="waktu" class="form-control" placeholder="Masukkan Waktu Kegiatan">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Bayar</label>
                        <input type="text" name="tanggal_bayar" class="form-control" placeholder="Masukkan Tanggal Bayar">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Masukkan Lokasi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vol</label>
                        <input type="text" name="vol" class="form-control" placeholder="Masukkan Volume">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control" placeholder="Masukkan Satuan">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Biaya</label>
                        <input type="text" name="biaya_display" id="biaya_display" class="form-control" placeholder="Rp. 0,-" autocomplete="off">
                        <input type="hidden" name="biaya" id="biaya">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PPK</label>
                        <select name="ppk" id="ppk" class="form-control"></select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIP PPK</label>
                        <input type="text" name="nip_ppk" id="nip_ppk" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kepala</label>
                        <input type="text" name="kepala" class="form-control" value="<?= isset($kepala) ? $kepala->nama : '' ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIP Kepala</label>
                        <input type="text" name="nip_kepala" class="form-control" value="<?= isset($kepala) ? $kepala->nip : '' ?>" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Generate DOCX</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================= -->

<!-- ===================== MODAL TIMELINE ===================== -->
<div class="modal fade" id="tlModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Timeline Dokumen</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="tl_alert" class="alert d-none"></div>
                <div id="logContainer" class="mb-4"></div>
                <hr>
                <form id="formTL">
                    <input type="hidden" name="id_dokumen" id="tl_id">
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
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================= -->

<?php foreach ($documents as $doc): ?>

    <div class="modal fade" id="revisiModal<?= $doc->id ?>" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Revisi Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form method="post" action="<?= base_url('docxgenerator/edit_draft') ?>" class="needs-validation" novalidate>

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

                        <button type="submit" class="btn btn-primary">Simpan Draft</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- ============================================================= -->

<script>
    // ── Form validation ──────────────────────────────────────────────
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // ── Select2: Kab/Kota ────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $('#regency_id').length) {
            $('#regency_id').select2({
                dropdownParent: $('#tambahDokumenModal'),
                placeholder: 'Ketik nama Kab/Kota...',
                minimumInputLength: 2,
                width: '100%',
                allowClear: true,
                ajax: {
                    url: "<?= base_url('docxgenerator/search_regencies') ?>",
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({ results: data })
                }
            });

            $('#regency_id').on('select2:select', function (e) {
                const d = e.params.data;
                document.getElementById('provinsi').value    = d.province    || '';
                document.getElementById('province_id').value = d.province_id || '';
            });

            $('#regency_id').on('select2:clear', function () {
                document.getElementById('provinsi').value    = '';
                document.getElementById('province_id').value = '';
            });
        }
    });

    // ── Select2: PPK ─────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $('#ppk').length) {
            $('#ppk').select2({
                dropdownParent: $('#tambahDokumenModal'),
                placeholder: 'Pilih PPK...',
                minimumInputLength: 1,
                width: '100%',
                ajax: {
                    url: "<?= base_url('docxgenerator/search_ppk') ?>",
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({ results: data })
                }
            });

            $('#ppk').on('select2:select', function (e) {
                $('#nip_ppk').val(e.params.data.nip || '');
            });

            $('#ppk').on('select2:clear', function () {
                $('#nip_ppk').val('');
            });
        }
    });

    // ── Format Rupiah ────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const display = document.getElementById('biaya_display');
        const hidden  = document.getElementById('biaya');

        function formatRupiah(angka) {
            let str  = angka.toString();
            let sisa = str.length % 3;
            let rp   = str.substr(0, sisa);
            let ribu = str.substr(sisa).match(/\d{3}/g);
            if (ribu) rp += (sisa ? '.' : '') + ribu.join('.');
            return 'Rp. ' + rp;
        }

        display.addEventListener('input', function () {
            let value = this.value.replace(/[^\d]/g, '').replace(/^0+(?=\d)/, '');
            hidden.value = value;
            this.value   = value === '' ? '' : formatRupiah(value);
        });

        display.addEventListener('paste', e => e.preventDefault());
    });

    // ── Anggaran dropdown ────────────────────────────────────────────
    document.getElementById('anggaran_id').addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        document.getElementById('kode_anggaran').value = opt.dataset.kode || '';
        document.getElementById('akun_anggaran').value = opt.dataset.nama || '';
    });

    // ── Modal Tambah Dokumen ─────────────────────────────────────────
    // PERBAIKAN: definisikan openTambahDokumenModal di scope global,
    // tapi buat instance modal di dalam DOMContentLoaded agar elemen sudah ada.
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl    = document.getElementById('tambahDokumenModal');
        const tambahModal = new bootstrap.Modal(modalEl);

        window.openTambahDokumenModal = function () {
            const form = modalEl.querySelector('form');
            form.reset();
            form.classList.remove('was-validated');

            // Reset select2 jika ada
            if (window.jQuery) {
                $('#regency_id').val(null).trigger('change');
                $('#ppk').val(null).trigger('change');
                $('#nip_ppk').val('');
                document.getElementById('provinsi').value    = '';
                document.getElementById('province_id').value = '';
            }

            tambahModal.show();
        };
    });

    // ── Hapus Dokumen ────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        window.hapusDokumen = function (id) {
            if (!confirm('Yakin hapus dokumen ini?')) return;
            fetch("<?= base_url('docxgenerator/hapus/') ?>" + id)
            .then(res => res.json())
            .then(data => {
                const msg = document.getElementById('message');
                msg.textContent = data.message;
                msg.className   = data.status ? 'alert alert-success mt-3' : 'alert alert-danger mt-3';
                msg.classList.remove('d-none');
                if (data.status) setTimeout(() => location.reload(), 1000);
            });
        };
    });

    // ── Delete modal: isi id & nama ──────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('deleteDocId').value      = this.dataset.id;
                document.getElementById('deleteDocName').textContent = this.dataset.nama;
            });
        });
    });

    // ── Docx Viewer ──────────────────────────────────────────────────
    // PERBAIKAN: hapus $d->file_doc (PHP di luar loop = error)
    function openDocxViewer(fileName) {
        const fileUrl   = "<?= base_url('docxgenerator/view_docx/') ?>" + fileName;
        const viewerUrl = "https://docs.google.com/gview?embedded=true&url=" + encodeURIComponent(fileUrl);
        document.getElementById('docxViewer').src = viewerUrl;
    }


    document.addEventListener('DOMContentLoaded', function () {
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