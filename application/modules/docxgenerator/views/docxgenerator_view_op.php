<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
        <!--
        <hr class="my-2">
        <a class="btn btn-sm btn-success" href="<?= base_url('docxgenerator/download/'.$this->session->flashdata('file_docx')) ?>">
            Download Dokumen DOCX
        </a>
         <a class="btn btn-sm btn-primary" href="<?= base_url('docxgenerator/download_pdf/'.$this->session->flashdata('file_pdf')) ?>">
            Download Dokumen PDF
        </a> -->
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
            <th>Pembuat</th>
            <th>PPK</th>
            <th>File</th>
            <th>Aksi</th>
            <th>Pengajuan</th>
            <th>View</th>
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
                <td><?= htmlspecialchars($d->nip_creator) ?></td>
                <td><?= htmlspecialchars($d->ppk_id) ?></td>
                <td>
                    <a class="btn btn-sm btn-success" href="<?= base_url('docxgenerator/download_file/'.$d->file_doc) ?>">Download</a>
                </td>
                <td>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $d->id ?>"data-nama="<?= htmlspecialchars($d->nama_kegiatan) ?>" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        Hapus
                    </button>
                </td>

                <td>
                    <?php if ($d->status === 'draft'): ?>
                        <a class="btn btn-sm btn-primary" href="<?= base_url('docxgenerator/pengajuan/'.$d->id) ?>">Ajukan Draft</a>
                    <?php elseif ($d->status === 'revisi'): ?>
                        <a class="btn btn-sm btn-primary" href="<?= base_url('docxgenerator/timeline_dok/'.$d->id) ?>">Revisi</a>
                    <?php else: ?>
                        <span><?= htmlspecialchars($d->status) ?></span>
                    <?php endif; ?>
                </td>
                
                <td>
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#docxModal" onclick="openDocxViewer('<?= $d->file_doc ?>')">Lihat Dokumen</button>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="docxModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <iframe id="docxViewer"
                style="width:100%; height:80vh;"
                frameborder="0">
            </iframe>
        </div>

    </div>
</div>
</div>


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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahDokumenModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="post" action="<?= base_url('docxgenerator/generate') ?>" class="needs-validation" novalidate>
                    <!-- Unit Organisasi -->
                    <div class="mb-3">
                        <label class="form-label">Unit Organisasi <span class="text-danger">*</span></label>
                        <input type="text" name="unit_organisasi" class="form-control" placeholder="Masukkan Unit Organisasi" required>
                        <div class="invalid-feedback">Unit Organisasi wajib diisi.</div>
                    </div>

                    <!-- Program -->
                    <div class="mb-3">
                        <label class="form-label">Program <span class="text-danger">*</span></label>
                        <input type="text" name="program" class="form-control" placeholder="Masukkan Program" required>
                        <div class="invalid-feedback">Program wajib diisi.</div>
                    </div>

                    <!-- Kegiatan -->
                    <div class="mb-3">
                        <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="kegiatan" class="form-control" placeholder="Masukkan Kegiatan" required>
                        <div class="invalid-feedback">Kegiatan wajib diisi.</div>
                    </div>

                    <!-- KRO -->
                    <div class="mb-3">
                        <label class="form-label">KRO <span class="text-danger">*</span></label>
                        <input type="text" name="kro" class="form-control" placeholder="Masukkan KRO" required>
                        <div class="invalid-feedback">KRO wajib diisi.</div>
                    </div>

                    <!-- RO -->
                    <div class="mb-3">
                        <label class="form-label">RO <span class="text-danger">*</span></label>
                        <input type="text" name="ro" class="form-control" placeholder="Masukkan RO" required>
                        <div class="invalid-feedback">RO wajib diisi.</div>
                    </div>

                    <!-- Komponen -->
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
                                <option value="<?= $row->id ?>" data-kode="<?= $row->kode_akun ?>" data-nama="<?= $row->nama_kegiatan ?>"> <?= $row->kode_akun ?> - <?= $row->nama_kegiatan ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Anggaran wajib dipilih.</div>
                    </div>

                    <input type="hidden" name="kode_anggaran" id="kode_anggaran">
                    <input type="hidden" name="akun_anggaran" id="akun_anggaran">

                    <!-- Kab/Kota Kegiatan (autocomplete) -->
                    <div class="mb-3">
                        <label class="form-label">Kab/Kota Kegiatan <span class="text-danger">*</span></label>
                        <select name="regency_id" id="regency_id" class="form-control" required></select>
                        <div class="invalid-feedback">Kab/Kota wajib dipilih.</div>
                    </div>


                    <!-- Provinsi (otomatis) -->
                    <div class="mb-3">
                        <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                        <input type="text" name="provinsi" id="provinsi" class="form-control" placeholder="Terisi otomatis dari Kab/Kota" readonly required>
                        <input type="hidden" name="province_id" id="province_id">
                        <div class="invalid-feedback">Provinsi wajib terisi.</div>
                    </div>


                    <!-- Tahun Anggaran -->
                    <div class="mb-3">
                        <label class="form-label">Tahun Anggaran <span class="text-danger">*</span></label>
                        <input type="text" name="tahun_anggaran" class="form-control" placeholder="Masukkan Tahun Anggaran" required>
                        <div class="invalid-feedback">Tahun Anggaran wajib diisi.</div>
                    </div>

                    <!-- Item Dasar Hukum -->
                    <div class="mb-3">
                        <label class="form-label">Dasar Hukum <span class="text-danger">*</span></label>
                        <textarea name="dasar_hukum" class="form-control" placeholder="Jangan masukkan nomor urut, cukup pisahkan dengan ENTER"></textarea>
                    </div>

                    <!-- Gambaran Umum -->
                    <div class="mb-3">
                        <label class="form-label">Gambaran Umum</label>
                        <textarea name="gambaran_umum" class="form-control" placeholder="Masukkan Gambaran Umum"></textarea>
                    </div>

                    <!-- Maksud dan Tujuan -->
                    <div class="mb-3">
                        <label class="form-label">Maksud dan Tujuan</label>
                        <textarea name="maksud_tujuan" class="form-control" placeholder="Masukkan Maksud dan Tujuan"></textarea>
                    </div>

                    <!-- Keluaran/Output -->
                    <div class="mb-3">
                        <label class="form-label">Keluaran/Output</label>
                        <textarea name="keluaran" class="form-control" placeholder="Masukkan Keluaran/Output"></textarea>
                    </div>

                    <!-- Nama Kegiatan -->
                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" placeholder="Masukkan Nama Kegiatan">
                    </div>

                    <!-- Waktu -->
                    <div class="mb-3">
                        <label class="form-label">Waktu</label>
                        <input type="text" name="waktu" class="form-control" placeholder="Masukkan Waktu Kegiatan">
                    </div>

                    <!-- Tanggal Bayar -->
                    <div class="mb-3">
                        <label class="form-label">Tanggal Bayar</label>
                        <input type="text" name="tanggal_bayar" class="form-control" placeholder="Masukkan Tanggal Bayar">
                    </div>

                    <!-- Lokasi -->
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
                        <input type="text" name="biaya_display" id="biaya_display" class="form-control" placeholder="Rp. 0,-"autocomplete="off">

                        <!-- nilai asli untuk POST -->
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

                    <button type="submit" class="btn btn-primary">
                        Generate DOCX
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- MODAL -->
<div class="modal fade" id="tlModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Timeline Dokumen</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- ALERT -->
                <div id="tl_alert" class="alert d-none"></div>

                <!-- TIMELINE -->
                <div id="logContainer" class="mb-4"></div>


                <!-- TIMELINE -->
                <div id="logContainer" class="mb-4"></div>

                <hr>

                <!-- FORM -->
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



<script>
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


    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $('#regency_id').length) {
            $('#regency_id').select2({
                dropdownParent: $('#tambahDokumenModal'), // ← INI KUNCI
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
                    }
                }
            });


            $('#regency_id').on('select2:select', function (e) {
                const d = e.params.data;
                document.getElementById('provinsi').value = d.province || '';
                document.getElementById('province_id').value = d.province_id || '';
            });

            $('#regency_id').on('select2:clear', function () {
                document.getElementById('provinsi').value = '';
                document.getElementById('province_id').value = '';
            });
        }
    });
    
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $('#ppk').length) {
            $('#ppk').select2({
                dropdownParent: $('#tambahDokumenModal'), // ← WAJIB
                placeholder: 'Pilih PPK...',
                minimumInputLength: 1,
                width: '100%',
                ajax: {
                    url: "<?= base_url('docxgenerator/search_ppk') ?>",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return { results: data };
                    }
                }
            });

            $('#ppk').on('select2:select', function (e) {
                const d = e.params.data;
                $('#nip_ppk').val(d.nip || '');
            });

            $('#ppk').on('select2:clear', function () {
                $('#nip_ppk').val('');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const display = document.getElementById('biaya_display');
        const hidden  = document.getElementById('biaya');

        display.addEventListener('input', function () {
            let value = this.value.replace(/[^\d]/g, '');

        // hilangkan leading zero
            value = value.replace(/^0+(?=\d)/, '');

            hidden.value = value;

            if (value === '') {
                this.value = '';
                return;
            }

            this.value = formatRupiah(value);
        });

        display.addEventListener('paste', function (e) {
            e.preventDefault();
        });

        function formatRupiah(angka) {
            let numberString = angka.toString();
            let sisa = numberString.length % 3;
            let rupiah = numberString.substr(0, sisa);
            let ribuan = numberString.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return 'Rp. ' + rupiah;
        }
    });



    document.getElementById('anggaran_id').addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];

        document.getElementById('kode_anggaran').value = opt.dataset.kode || '';
        document.getElementById('akun_anggaran').value = opt.dataset.nama || '';
    });

    document.addEventListener('DOMContentLoaded', function () {

        const tambahModal = new bootstrap.Modal(
            document.getElementById('tambahDokumenModal')
            );

        window.openTambahDokumenModal = function () {
            const form = document.querySelector('#tambahDokumenModal form');
            form.reset();
            form.classList.remove('was-validated');
            tambahModal.show();
        };

        window.hapusDokumen = function (id) {
            if (!confirm('Yakin hapus dokumen ini?')) return;

            fetch("<?= base_url('docxgenerator/hapus/') ?>" + id)
            .then(res => res.json())
            .then(data => {
                message.textContent = data.message;
                message.className = data.status
                ? 'alert alert-success mt-3'
                : 'alert alert-danger mt-3';
                message.classList.remove('d-none');

                if (data.status) {
                    setTimeout(() => location.reload(), 1000);
                }
            });
        };

    });

    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        const docIdInput = document.getElementById('deleteDocId');
        const docNameText = document.getElementById('deleteDocName');

        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                docIdInput.value = this.dataset.id;
                docNameText.textContent = this.dataset.nama;
            });
        });
    });

    function openDocxViewer(fileName) {

        const token = "<?= hash_hmac(
            'sha256',
            $d->file_doc . '.docx',
            'KUNCI_RAHASIA_APP'
            ) ?>";

        const fileUrl =
        "<?= base_url('docxgenerator/view_docx/') ?>" +
        fileName + "?token=" + token;

        const viewerUrl =
        "https://docs.google.com/gview?embedded=true&url=" +
        encodeURIComponent(fileUrl);

        document.getElementById('docxViewer').src = viewerUrl;
    }



</script>
