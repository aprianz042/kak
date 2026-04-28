<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="h5 mb-0">Timeline Dokumen</h3>
</div>

<div id="message" class="mt-3 d-none"></div>

<?php 
$badge = [
    'draft'       => 'secondary',
    'ajuan_baru'  => 'primary',
    'revisi'      => 'warning',
    'disetujui'   => 'success'
];

// cari index revisi terakhir
$lastRevisiIndex = null;
foreach ($timeline as $i => $d) {
    if ($d->status === 'revisi') {
        $lastRevisiIndex = $i;
    }
}
?>

<?php foreach ($timeline as $i => $d): ?>
    <div class="mb-3 p-3 border rounded bg-light">
        <div class="d-flex justify-content-between">
            <span class="badge bg-<?= $badge[$d->status] ?? 'secondary' ?>">
                <?= $d->status ?>
            </span>
            <small><?= $d->created_at ?></small>
        </div>

        <div class="mt-2">
            <strong><?= $d->pengirim ?></strong> ➝ 
            <strong><?= $d->penerima ?></strong>
        </div>

        <div class="mt-2 text-muted">
            <?= $d->pesan ?>
        </div>

        <?php if ($d->status === 'revisi' && $i === $lastRevisiIndex): ?>
        <div class="mt-2">
            <button class="btn btn-sm btn-warning" 
                    data-bs-toggle="modal" 
                    data-bs-target="#revisiModal<?= $d->id_dokumen ?>">
                Upload Revisi
            </button>
        </div>
        <?php endif; ?>
    </div>

<?php endforeach; ?>


<?php if ($d->status === 'revisi' && $i === $lastRevisiIndex): ?>
<div class="modal fade" id="revisiModal<?= $d->id_dokumen ?>" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Revisi Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

<form method="post" action="<?= base_url('docxgenerator/revisi') ?>" class="needs-validation" novalidate>

<input type="hidden" name="id_dokumen" value="<?= $d->id_dokumen ?>">

<!-- Unit Organisasi -->
<div class="mb-3">
    <label class="form-label">Unit Organisasi</label>
    <input type="text" name="unit_organisasi" class="form-control" value="<?= $d->unit_organisasi ?? '' ?>" required>
</div>

<!-- Program -->
<div class="mb-3">
    <label class="form-label">Program</label>
    <input type="text" name="program" class="form-control" value="<?= $d->program ?? '' ?>" required>
</div>

<!-- Kegiatan -->
<div class="mb-3">
    <label class="form-label">Kegiatan</label>
    <input type="text" name="kegiatan" class="form-control" value="<?= $d->kegiatan ?? '' ?>" required>
</div>

<!-- KRO -->
<div class="mb-3">
    <label class="form-label">KRO</label>
    <input type="text" name="kro" class="form-control" value="<?= $d->kro ?? '' ?>" required>
</div>

<!-- RO -->
<div class="mb-3">
    <label class="form-label">RO</label>
    <input type="text" name="ro" class="form-control" value="<?= $d->ro ?? '' ?>" required>
</div>

<!-- Komponen -->
<div class="mb-3">
    <label class="form-label">Komponen</label>
    <input type="text" name="komponen" class="form-control" value="<?= $d->komponen ?? '' ?>" required>
</div>

<!-- Anggaran -->
<div class="mb-3">
    <label class="form-label">Anggaran</label>
    <select name="anggaran_id" class="form-control" required>
        <option value="">-- Pilih Anggaran --</option>
        <?php foreach ($anggaran as $row): ?>
            <option value="<?= $row->id ?>" <?= ($d->anggaran_id ?? '') == $row->id ? 'selected' : '' ?>>
                <?= $row->kode_akun ?> - <?= $row->nama_kegiatan ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Kab/Kota -->
<div class="mb-3">
    <label class="form-label">Kab/Kota</label>
    <select name="regency_id" class="form-control" required></select>
</div>

<!-- Provinsi -->
<div class="mb-3">
    <label class="form-label">Provinsi</label>
    <input type="text" name="provinsi" class="form-control" value="<?= $d->provinsi ?? '' ?>" readonly>
</div>

<!-- Tahun -->
<div class="mb-3">
    <label class="form-label">Tahun Anggaran</label>
    <input type="text" name="tahun_anggaran" class="form-control" value="<?= $d->tahun_anggaran ?? '' ?>" required>
</div>

<!-- Dasar Hukum -->
<div class="mb-3">
    <label class="form-label">Dasar Hukum</label>
    <textarea name="dasar_hukum" class="form-control"><?= $d->dasar_hukum ?? '' ?></textarea>
</div>

<!-- Gambaran Umum -->
<div class="mb-3">
    <label class="form-label">Gambaran Umum</label>
    <textarea name="gambaran_umum" class="form-control"><?= $d->gambaran_umum ?? '' ?></textarea>
</div>

<!-- Maksud -->
<div class="mb-3">
    <label class="form-label">Maksud dan Tujuan</label>
    <textarea name="maksud_tujuan" class="form-control"><?= $d->maksud_tujuan ?? '' ?></textarea>
</div>

<!-- Output -->
<div class="mb-3">
    <label class="form-label">Keluaran</label>
    <textarea name="keluaran" class="form-control"><?= $d->keluaran ?? '' ?></textarea>
</div>

<!-- Nama -->
<div class="mb-3">
    <label class="form-label">Nama Kegiatan</label>
    <input type="text" name="nama_kegiatan" class="form-control" value="<?= $d->nama_kegiatan ?? '' ?>">
</div>

<!-- Waktu -->
<div class="mb-3">
    <label class="form-label">Waktu</label>
    <input type="text" name="waktu" class="form-control" value="<?= $d->waktu ?? '' ?>">
</div>

<!-- Tanggal Bayar -->
<div class="mb-3">
    <label class="form-label">Tanggal Bayar</label>
    <input type="text" name="tanggal_bayar" class="form-control" value="<?= $d->tanggal_bayar ?? '' ?>">
</div>

<!-- Lokasi -->
<div class="mb-3">
    <label class="form-label">Lokasi</label>
    <input type="text" name="lokasi" class="form-control" value="<?= $d->lokasi ?? '' ?>">
</div>

<!-- Volume -->
<div class="mb-3">
    <label class="form-label">Vol</label>
    <input type="text" name="vol" class="form-control" value="<?= $d->vol ?? '' ?>">
</div>

<!-- Satuan -->
<div class="mb-3">
    <label class="form-label">Satuan</label>
    <input type="text" name="satuan" class="form-control" value="<?= $d->satuan ?? '' ?>">
</div>

<!-- Biaya -->
<div class="mb-3">
    <label class="form-label">Biaya</label>
    <input type="text" name="biaya" class="form-control" value="<?= $d->biaya ?? '' ?>">
</div>

<!-- PPK -->
<div class="mb-3">
    <label class="form-label">PPK</label>
    <select name="ppk" class="form-control"></select>
</div>

<!-- NIP PPK -->
<div class="mb-3">
    <label class="form-label">NIP PPK</label>
    <input type="text" name="nip_ppk" class="form-control" value="<?= $d->nip_ppk ?? '' ?>" readonly>
</div>

<!-- Kepala -->
<div class="mb-3">
    <label class="form-label">Kepala</label>
    <input type="text" class="form-control" value="<?= isset($kepala) ? $kepala->nama : '' ?>" readonly>
</div>

<!-- NIP Kepala -->
<div class="mb-3">
    <label class="form-label">NIP Kepala</label>
    <input type="text" class="form-control" value="<?= isset($kepala) ? $kepala->nip : '' ?>" readonly>
</div>

<button type="submit" class="btn btn-warning">Simpan Revisi</button>

</form>

            </div>
        </div>
    </div>
</div>
<?php endif; ?>


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