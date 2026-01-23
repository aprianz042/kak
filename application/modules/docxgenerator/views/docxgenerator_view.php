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

                    <!-- Kode Anggaran -->
                    <div class="mb-3">
                        <label class="form-label">Kode Anggaran <span class="text-danger">*</span></label>
                        <input type="text" name="kode_anggaran" class="form-control" placeholder="Masukkan Kode Anggaran" required>
                        <div class="invalid-feedback">Kode Anggaran wajib diisi.</div>
                    </div>

                    <!-- Akun Anggaran -->
                    <div class="mb-3">
                        <label class="form-label">Akun Anggaran <span class="text-danger">*</span></label>
                        <input type="text" name="akun_anggaran" class="form-control" placeholder="Masukkan Akun Anggaran" required>
                        <div class="invalid-feedback">Akun Anggaran wajib diisi.</div>
                    </div>

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
                        <textarea name="dasar_hukum" class="form-control" placeholder="Masukkan Dasar Hukum"></textarea>
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
                        <input type="text" name="biaya" class="form-control" placeholder="Masukkan Biaya">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PPK</label>
                        <input type="text" name="ppk" class="form-control" placeholder="Nama PPK">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIP PPK</label>
                        <input type="text" name="nip_ppk" class="form-control" placeholder="NIP PPK">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kepala</label>
                        <input type="text" name="kepala" class="form-control" placeholder="Nama Kepala">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIP Kepala</label>
                        <input type="text" name="nip_kepala" class="form-control" placeholder="NIP Kepala">
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
    
</script>
