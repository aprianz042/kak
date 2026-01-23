<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="h5 mb-0">Daftar Pegawai</h3>
    <button type="button" class="btn btn-primary" onclick="openTambahModal()">Tambah Pegawai</button>
</div>

<div id="message" class="mt-3 d-none"></div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Email</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pegawai as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($p->nip) ?></td>
                <td><?= $p->email ?></td>
                <td><?= htmlspecialchars($p->nama) ?></td>
                <td><?= htmlspecialchars($p->jabatan) ?></td>
                <td><?= ucfirst($p->role) ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning" onclick="openEditModal(<?= $p->id ?>)">Edit</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusPegawai(<?= $p->id ?>)"> Hapus</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- MODAL TAMBAH PEGAWAI -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formTambahPegawai" class="needs-validation" novalidate>

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nip"
                        placeholder="Masukkan NIP pegawai" required>
                        <div class="invalid-feedback">NIP wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email"
                        placeholder="Masukkan email pegawai" required>
                        <div class="invalid-feedback">Email wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama"
                        placeholder="Masukkan nama lengkap pegawai" required>
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
            <form id="formEditPegawai" novalidate class="needs-validation">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-2">
                        <label for="edit_nip" class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nip" name="nip" placeholder="NIP pegawai" required>
                        <div class="invalid-feedback">NIP wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" placeholder="Email pegawai" required>
                        <div class="invalid-feedback">Email wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label for="edit_nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" placeholder="Nama lengkap pegawai" required>
                        <div class="invalid-feedback">Nama wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label for="edit_jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_jabatan" name="jabatan" placeholder="Jabatan pegawai" required>
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
            const form = document.getElementById('formTambahPegawai');
            form.reset();
            form.classList.remove('was-validated');
            tambahModal.show();
        };

    // =============================
    // SUBMIT FORM TAMBAH PEGAWAI
    // =============================
        document.getElementById('formTambahPegawai')
        .addEventListener('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);

            fetch("<?= site_url('pegawai/simpan') ?>", {
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
        window.hapusPegawai = function(id) {
            if (!confirm('Yakin hapus data ini?')) return;

            fetch("<?= site_url('pegawai/hapus/') ?>" + id)
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
            fetch("<?= site_url('pegawai/get/') ?>" + id)
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
            .catch(() => alert('Gagal mengambil data pegawai'));
        };

    // =============================
    // SUBMIT FORM EDIT
    // =============================
        document.getElementById('formEditPegawai')
        .addEventListener('submit', function(e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);

            fetch("<?= site_url('pegawai/update') ?>", {
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
