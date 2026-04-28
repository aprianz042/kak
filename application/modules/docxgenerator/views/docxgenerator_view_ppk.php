<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="h5 mb-0">Daftar Usulan Dokumen</h3>
</div>

<div id="message" class="mt-3 d-none"></div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kegiatan</th>
            <th>Tahun</th>
            <th>Kota</th>
            <th>Provinsi</th>
            <th>Kode</th>
            <th>Akun</th>
            <th>Pembuat</th>
            <th>File</th>
            <th>Tindak Lanjut</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($documents as $i => $d): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= $d->nama_kegiatan ?></td>
                <td><?= $d->tahun_anggaran ?></td>
                <td><?= $d->kota_kegiatan ?></td>
                <td><?= $d->provinsi ?></td>
                <td><?= $d->kode_anggaran ?></td>
                <td><?= $d->akun_anggaran ?></td>
                <td><?= $d->nip_creator ?></td>
                <td>
                    <a class="btn btn-sm btn-success"
                        href="<?= base_url('docxgenerator/download_file/'.$d->file_doc) ?>">
                        Download
                    </a>
                </td>

                <td>
                    <button class="btn btn-sm btn-danger btn-log" data-id="<?= $d->id ?>" data-bs-toggle="modal" data-bs-target="#tlModal">Tindak Lanjut</button>
                </td>

                <td><?= $d->status ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

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

    /* ================= CLICK BUTTON ================= */
        document.querySelectorAll('.btn-log').forEach(btn => {
            btn.addEventListener('click', function () {

                const id = this.dataset.id;

            // SET ID KE FORM (INI YANG SERING KELEWAT)
                document.getElementById('tl_id').value = id;

            // RESET FORM
                document.getElementById('formTL').reset();
                document.getElementById('wrap_pesan').classList.add('d-none');

                loadLog(id);
            });
        });

    /* ================= STATUS CHANGE ================= */
        document.getElementById('tl_status').addEventListener('change', function(){

            const wrap = document.getElementById('wrap_pesan');

            if (this.value === 'revisi') {
                wrap.classList.remove('d-none');
            } else {
                wrap.classList.add('d-none');
            }
        });

    /* ================= SUBMIT ================= */
        document.getElementById('formTL').addEventListener('submit', function(e){
            e.preventDefault();

            const formData = new FormData(this);
            const alertBox = document.getElementById('tl_alert');

            fetch("<?= base_url('docxgenerator/tindak_lanjut') ?>", {
                method:'POST',
                body:formData
            })
            .then(res => res.json())
            .then(res => {

                // tampilkan alert
                alertBox.classList.remove('d-none');
                alertBox.classList.remove('alert-success','alert-danger');

                if(res.status){

                    alertBox.classList.add('alert-success');
                    alertBox.innerText = 'Berhasil diproses';

                    const id = formData.get('id_dokumen');
                    loadLog(id);

                    document.getElementById('tl_pesan').value = '';

                } else {

                    alertBox.classList.add('alert-danger');
                    alertBox.innerText = res.message || 'Gagal diproses';
                }

                // 🔥 tunggu 2 detik lalu tutup modal
                setTimeout(() => {

                    const modalEl = document.getElementById('tlModal');
                    let modal = bootstrap.Modal.getInstance(modalEl);

                    if (!modal) {
                        modal = new bootstrap.Modal(modalEl);
                    }

                    modal.hide();

                    // reset alert
                    alertBox.classList.add('d-none');
                    alertBox.innerText = '';

                }, 2000);

            })
            .catch(err => {
                console.error(err);

                alertBox.classList.remove('d-none');
                alertBox.classList.add('alert-danger');
                alertBox.innerText = 'Terjadi kesalahan server';
            });
        });

    });
</script>