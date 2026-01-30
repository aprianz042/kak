<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
        <hr class="my-2">
        <a class="btn btn-sm btn-success"
        href="<?= base_url('docxgenerator/download/'.$this->session->flashdata('file_docx')) ?>">
        Download Dokumen DOCX
    </a>
    <a class="btn btn-sm btn-primary"
    href="<?= base_url('docxgenerator/download_pdf/'.$this->session->flashdata('file_pdf')) ?>">
    Download Dokumen PDF
</a>
</div>
<?php endif; ?>


<div class="row">
    <!-- Kolom kiri -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3">Generate Dokumen DOCX</h4>

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


</script>
