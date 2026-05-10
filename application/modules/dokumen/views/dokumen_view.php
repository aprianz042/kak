<div id="message" class="mt-3 d-none"></div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kegiatan</th>
            <th>Kota Kegiatan</th>
            <th>Kode Anggaran</th>
            <th>Akun</th>
            <th>Biaya</th>
            <th>PPK</th>
            <!-- <th>Aksi</th> -->
            <th>Dokumen</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dokumen as $i => $d): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($d->nama_kegiatan) ?></td>
                <td><?= htmlspecialchars($d->kota_kegiatan) ?></td>
                <td><?= htmlspecialchars($d->kode_anggaran) ?></td>
                <td><?= htmlspecialchars($d->akun_anggaran) ?></td>
                <td><?= htmlspecialchars($d->biaya) ?></td>
                <td><?= htmlspecialchars($d->nama_ppk) ?></td>

                <td>
                    <a class="btn btn-sm btn-success w-100" href="<?= base_url('docxgenerator/timeline_dok/'.$d->id) ?>"><i class="fa fa-eye"></i> Lihat Dokumen</a>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- MODAL TAMBAH PEGAWAI -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formTambahDokumen" class="needs-validation" novalidate>

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nip"
                        placeholder="Masukkan NIP dokumen" required>
                        <div class="invalid-feedback">NIP wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email"
                        placeholder="Masukkan email dokumen" required>
                        <div class="invalid-feedback">Email wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama"
                        placeholder="Masukkan nama lengkap dokumen" required>
                        <div class="invalid-feedback">Nama wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="jabatan"
                        placeholder="Contoh: Analis Data, Staf TU" required>
                        <div class="invalid-feedback">Jabatan wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" name="role" required>
                            <option value="">Pilih role pengguna</option>
                            <option value="admin">Admin</option>
                            <option value="kepala">Kepala</option>
                            <option value="ppk">PPK</option>
                            <option value="operator">Operator</option>
                        </select>
                        <div class="invalid-feedback">Role wajib dipilih.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="pass"
                        placeholder="Minimal 6 karakter" required>
                        <div class="invalid-feedback">Password wajib diisi.</div>
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


<!-- MODAL EDIT -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditDokumen" novalidate class="needs-validation">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-2">
                        <label for="edit_nip" class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nip" name="nip" placeholder="NIP dokumen" required>
                        <div class="invalid-feedback">NIP wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" placeholder="Email dokumen" required>
                        <div class="invalid-feedback">Email wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label for="edit_nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" placeholder="Nama lengkap dokumen" required>
                        <div class="invalid-feedback">Nama wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label for="edit_jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_jabatan" name="jabatan" placeholder="Jabatan dokumen" required>
                        <div class="invalid-feedback">Jabatan wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label for="edit_role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="kepala">Kepala</option>
                            <option value="ppk">PPK</option>
                            <option value="operator">Operator</option>
                        </select>
                        <div class="invalid-feedback">Role wajib dipilih.</div>
                    </div>

                    <div class="mb-2">
                        <label for="edit_pass" class="form-label">Password (opsional)</label>
                        <input type="password" class="form-control" id="edit_pass" name="pass" placeholder="Kosongkan jika tidak diubah">
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
    // MODAL TAMBAH
    // =============================
        const tambahModal = new bootstrap.Modal(
                                                document.getElementById('tambahModal')
                                                );

    // WAJIB GLOBAL (dipanggil dari onclick)
        window.openTambahModal = function () {
            const form = document.getElementById('formTambahDokumen');
            form.reset();
            form.classList.remove('was-validated');
            tambahModal.show();
        };

    // =============================
    // SUBMIT FORM TAMBAH PEGAWAI
    // =============================
        document.getElementById('formTambahDokumen')
        .addEventListener('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);

            fetch("<?= site_url('dokumen/simpan') ?>", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                messageDiv.textContent = data.message;
                messageDiv.className = data.status
                ? 'alert alert-success mt-3'
                : 'alert alert-danger mt-3';
                messageDiv.classList.remove('d-none');

                if (data.status) {
                    tambahModal.hide();
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(() => {
                messageDiv.textContent = 'Terjadi kesalahan sistem';
                messageDiv.className = 'alert alert-danger mt-3';
                messageDiv.classList.remove('d-none');
            });
        });

    // =============================
    // HAPUS PEGAWAI
    // =============================
        window.hapusDokumen = function(id) {
            if (!confirm('Yakin hapus data ini?')) return;

            fetch("<?= site_url('dokumen/hapus/') ?>" + id)
            .then(res => res.json())
            .then(data => {
                messageDiv.textContent = data.message;
                messageDiv.className = data.status
                ? 'alert alert-success mt-3'
                : 'alert alert-danger mt-3';
                messageDiv.classList.remove('d-none');

                if (data.status) {
                    setTimeout(() => location.reload(), 1000);
                }
            });
        };

    // =============================
    // MODAL EDIT
    // =============================
        const editModal = new bootstrap.Modal(
                                              document.getElementById('editModal')
                                              );

        window.openEditModal = function(id) {
            fetch("<?= site_url('dokumen/get/') ?>" + id)
            .then(res => res.json())
            .then(p => {
                edit_id.value      = p.id;
                edit_nip.value     = p.nip;
                edit_email.value   = p.email;
                edit_nama.value    = p.nama;
                edit_jabatan.value = p.jabatan;
                edit_role.value    = p.role;
                edit_pass.value    = '';

                editModal.show();
            })
            .catch(() => alert('Gagal mengambil data dokumen'));
        };

    // =============================
    // SUBMIT FORM EDIT
    // =============================
        document.getElementById('formEditDokumen')
        .addEventListener('submit', function(e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);

            fetch("<?= site_url('dokumen/update') ?>", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                messageDiv.textContent = data.message;
                messageDiv.className = data.status
                ? 'alert alert-success mt-3'
                : 'alert alert-danger mt-3';
                messageDiv.classList.remove('d-none');

                if (data.status) {
                    editModal.hide();
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan sistem');
            });
        });

    });
</script>
