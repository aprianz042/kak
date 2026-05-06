<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="h5 mb-0">Daftar Anggaran</h3>
    <?php if ($this->session->userdata('role') === 'operator') : ?>
    <button type="button" class="btn btn-primary" onclick="openTambahModal()">Tambah Anggaran</button>
    <?php endif; ?>
</div>

<div id="message" class="mt-3 d-none"></div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Akun</th>
            <th>Kegiatan</th>
            <th>Pagu</th>
            <?php if ($this->session->userdata('role') === 'operator') : ?>
                <th>Aksi</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($anggaran as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($p->kode_akun) ?></td>
                <td><?= htmlspecialchars($p->nama_kegiatan) ?></td>
                <td>Rp. <?= number_format($p->pagu, 0, ',', '.').",-" ?></td>
                <?php if ($this->session->userdata('role') === 'operator') : ?>
                <td>
                    <button type="button" class="btn btn-sm btn-warning" onclick="openEditModal(<?= $p->id ?>)">Edit</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusAnggaran(<?= $p->id ?>)">Hapus</button>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- MODAL TAMBAH ANGGARAN -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formTambahAnggaran" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Anggaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Kode Akun <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="kode_akun"
                            placeholder="Masukkan Kode Akun" required>
                        <div class="invalid-feedback">Kode Akun wajib diisi.</div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_kegiatan"
                            placeholder="Masukkan Nama Kegiatan" required>
                        <div class="invalid-feedback">Kegiatan wajib diisi.</div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Pagu <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" inputmode="numeric" pattern="[0-9.]*"
                                class="form-control" id="display_pagu"
                                placeholder="0" autocomplete="off">
                        </div>
                        <input type="hidden" name="pagu" id="raw_pagu">
                        <div class="text-danger small mt-1 d-none" id="pagu_error">Pagu wajib diisi.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT ANGGARAN -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditAnggaran" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Anggaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-2">
                        <label class="form-label">Kode Akun <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_kode_akun" name="kode_akun"
                            placeholder="Masukkan Kode Akun" required>
                        <div class="invalid-feedback">Kode Akun wajib diisi.</div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_kegiatan" name="nama_kegiatan"
                            placeholder="Masukkan Nama Kegiatan" required>
                        <div class="invalid-feedback">Kegiatan wajib diisi.</div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Pagu <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" inputmode="numeric" pattern="[0-9.]*"
                                class="form-control" id="edit_display_pagu"
                                placeholder="0" autocomplete="off">
                        </div>
                        <input type="hidden" name="pagu" id="edit_pagu">
                        <div class="text-danger small mt-1 d-none" id="edit_pagu_error">Pagu wajib diisi.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const messageDiv = document.getElementById('message');

        // =============================
        // INISIALISASI MODAL
        // =============================
        const tambahModal = new bootstrap.Modal(document.getElementById('tambahModal'));
        const editModal   = new bootstrap.Modal(document.getElementById('editModal'));

        // =============================
        // ELEMEN PAGU TAMBAH
        // =============================
        const displayPagu = document.getElementById('display_pagu'); // ✅ FIX: sebelumnya tidak dideklarasikan
        const rawPagu     = document.getElementById('raw_pagu');
        const paguError   = document.getElementById('pagu_error');

        // =============================
        // ELEMEN PAGU EDIT
        // =============================
        const editDisplayPagu = document.getElementById('edit_display_pagu');
        const editRawPagu     = document.getElementById('edit_pagu');
        const editPaguError   = document.getElementById('edit_pagu_error');

        // =============================
        // FORMAT RUPIAH — TAMBAH & EDIT
        // =============================
        // ✅ FIX: digabung jadi satu fungsi helper
        function setupFormatRupiah(displayEl, rawEl) {
            displayEl.addEventListener('input', function () {
                const angka = this.value.replace(/\D/g, '');
                this.value  = angka ? parseInt(angka).toLocaleString('id-ID') : '';
                rawEl.value = angka;
            });

            displayEl.addEventListener('keypress', function (e) {
                if (!/[0-9]/.test(e.key)) e.preventDefault();
            });

            // ✅ FIX: blokir paste non-angka
            displayEl.addEventListener('paste', function (e) {
                e.preventDefault();
                const teks  = (e.clipboardData || window.clipboardData).getData('text');
                const angka = teks.replace(/\D/g, '');
                this.value  = angka ? parseInt(angka).toLocaleString('id-ID') : '';
                rawEl.value = angka;
            });
        }

        setupFormatRupiah(displayPagu, rawPagu);
        setupFormatRupiah(editDisplayPagu, editRawPagu);

        // =============================
        // MODAL TAMBAH
        // =============================
        window.openTambahModal = function () {
            const form = document.getElementById('formTambahAnggaran');
            form.reset();
            form.classList.remove('was-validated');
            displayPagu.value = ''; // ✅ FIX: reset display pagu juga
            rawPagu.value     = '';
            paguError.classList.add('d-none');
            displayPagu.classList.remove('is-invalid');
            tambahModal.show();
        };

        // =============================
        // SUBMIT FORM TAMBAH
        // =============================
        document.getElementById('formTambahAnggaran').addEventListener('submit', function (e) {
            e.preventDefault();

            // ✅ FIX: validasi manual pagu karena pakai hidden input
            let valid = true;
            if (!rawPagu.value) {
                paguError.classList.remove('d-none');
                displayPagu.classList.add('is-invalid');
                valid = false;
            } else {
                paguError.classList.add('d-none');
                displayPagu.classList.remove('is-invalid');
            }

            if (!this.checkValidity() || !valid) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);

            fetch("<?= site_url('anggaran/simpan') ?>", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                tampilkanPesan(data.message, data.status);
                if (data.status) {
                    tambahModal.hide();
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(() => tampilkanPesan('Terjadi kesalahan sistem', false));
        });

        // =============================
        // MODAL EDIT — AMBIL DATA
        // =============================
        window.openEditModal = function (id) {
            fetch("<?= site_url('anggaran/get/') ?>" + id)
            .then(res => res.json())
            .then(p => {
                document.getElementById('edit_id').value            = p.id;
                document.getElementById('edit_kode_akun').value     = p.kode_akun;
                document.getElementById('edit_nama_kegiatan').value = p.nama_kegiatan;
                editRawPagu.value     = p.pagu;
                editDisplayPagu.value = parseInt(p.pagu).toLocaleString('id-ID'); // ✅ FIX: isi display pagu

                editPaguError.classList.add('d-none');
                editDisplayPagu.classList.remove('is-invalid');
                document.getElementById('formEditAnggaran').classList.remove('was-validated');
                editModal.show();
            })
            .catch(() => alert('Gagal mengambil data anggaran'));
        };

        // =============================
        // SUBMIT FORM EDIT
        // =============================
        document.getElementById('formEditAnggaran').addEventListener('submit', function (e) {
            e.preventDefault();

            // ✅ FIX: validasi manual pagu edit
            let valid = true;
            if (!editRawPagu.value) {
                editPaguError.classList.remove('d-none');
                editDisplayPagu.classList.add('is-invalid');
                valid = false;
            } else {
                editPaguError.classList.add('d-none');
                editDisplayPagu.classList.remove('is-invalid');
            }

            if (!this.checkValidity() || !valid) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);

            fetch("<?= site_url('anggaran/update') ?>", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                tampilkanPesan(data.message, data.status);
                if (data.status) {
                    editModal.hide();
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(() => tampilkanPesan('Terjadi kesalahan sistem', false));
        });

        // =============================
        // HAPUS ANGGARAN
        // =============================
        window.hapusAnggaran = function (id) {
            if (!confirm('Yakin hapus data ini?')) return;

            fetch("<?= site_url('anggaran/hapus/') ?>" + id)
            .then(res => res.json())
            .then(data => {
                tampilkanPesan(data.message, data.status);
                if (data.status) {
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(() => tampilkanPesan('Terjadi kesalahan sistem', false));
        };

        // =============================
        // HELPER: TAMPILKAN PESAN
        // =============================
        function tampilkanPesan(teks, berhasil) {
            messageDiv.textContent = teks;
            messageDiv.className   = berhasil
                ? 'alert alert-success mt-3'
                : 'alert alert-danger mt-3';
            messageDiv.classList.remove('d-none');
        }

    });
</script>