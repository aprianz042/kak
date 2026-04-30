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
    'ajuan_revisi'  => 'warning',
    'disetujui'   => 'success'
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

            <!-- STATUS -->
            <div>
                <span class="badge bg-<?= $badge[$d->status] ?? 'secondary' ?>">
                    <?= $d->status ?>
                </span>
            </div>

            <!-- TIMESTAMP (dibawah status) -->
            <div class="mt-1">
                <small><?= $d->created_at ?></small>
            </div>

            <!-- PENGIRIM → PENERIMA -->
            <div class="mt-2">
                <strong><?= $d->pengirim ?></strong> ➝ 
                <strong><?= $d->penerima ?></strong>
            </div>

            <!-- PESAN -->
            <div class="mt-2 text-muted">
                <?= $d->pesan ?>
            </div>

            <!-- BUTTON REVISI -->
            <?php if ($i === $lastIndex && $d->status === 'revisi'): ?>
                <div class="mt-3">
                    <button class="btn btn-sm btn-success" 
                    data-bs-toggle="modal" 
                    data-bs-target="#revisiModal<?= $d->id_dokumen ?>">
                    <i class="fa fa-edit"></i> Revisi
                </button>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php endforeach; ?>

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

                        <!-- Unit Organisasi -->
                        <div class="mb-3">
                            <label class="form-label">Unit Organisasi</label>
                            <input type="text" name="unit_organisasi" class="form-control" value="<?= $doc->unit_organisasi ?? '' ?>" required>
                        </div>

                        <!-- Program -->
                        <div class="mb-3">
                            <label class="form-label">Program</label>
                            <input type="text" name="program" class="form-control" value="<?= $doc->program ?? '' ?>" required>
                        </div>

                        <!-- Kegiatan -->
                        <div class="mb-3">
                            <label class="form-label">Kegiatan</label>
                            <input type="text" name="kegiatan" class="form-control" value="<?= $doc->kegiatan ?? '' ?>" required>
                        </div>

                        <!-- KRO -->
                        <div class="mb-3">
                            <label class="form-label">KRO</label>
                            <input type="text" name="kro" class="form-control" value="<?= $doc->kro ?? '' ?>" required>
                        </div>

                        <!-- RO -->
                        <div class="mb-3">
                            <label class="form-label">RO</label>
                            <input type="text" name="ro" class="form-control" value="<?= $doc->ro ?? '' ?>" required>
                        </div>

                        <!-- Komponen -->
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


                        <!-- Tahun -->
                        <div class="mb-3">
                            <label class="form-label">Tahun Anggaran</label>
                            <input type="text" name="tahun_anggaran" class="form-control" value="<?= $doc->tahun_anggaran ?? '' ?>" required>
                        </div>

                        <!-- Dasar Hukum -->
                        <div class="mb-3">
                            <label class="form-label">Dasar Hukum</label>
                            <textarea name="dasar_hukum" class="form-control"><?= $doc->dasar_hukum ?? '' ?></textarea>
                        </div>

                        <!-- Gambaran Umum -->
                        <div class="mb-3">
                            <label class="form-label">Gambaran Umum</label>
                            <textarea name="gambaran_umum" class="form-control"><?= $doc->gambaran_umum ?? '' ?></textarea>
                        </div>

                        <!-- Maksud -->
                        <div class="mb-3">
                            <label class="form-label">Maksud dan Tujuan</label>
                            <textarea name="maksud_tujuan" class="form-control"><?= $doc->maksud_tujuan ?? '' ?></textarea>
                        </div>

                        <!-- Output -->
                        <div class="mb-3">
                            <label class="form-label">Keluaran</label>
                            <textarea name="keluaran" class="form-control"><?= $doc->keluaran ?? '' ?></textarea>
                        </div>

                        <!-- Nama -->
                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control" value="<?= $doc->nama_kegiatan ?? '' ?>">
                        </div>

                        <!-- Waktu -->
                        <div class="mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="text" name="waktu" class="form-control" value="<?= $doc->waktu ?? '' ?>">
                        </div>

                        <!-- Tanggal Bayar -->
                        <div class="mb-3">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="text" name="tanggal_bayar" class="form-control" value="<?= $doc->tanggal_bayar ?? '' ?>">
                        </div>

                        <!-- Lokasi -->
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" value="<?= $doc->lokasi ?? '' ?>">
                        </div>

                        <!-- Volume -->
                        <div class="mb-3">
                            <label class="form-label">Vol</label>
                            <input type="text" name="vol" class="form-control" value="<?= $doc->vol ?? '' ?>">
                        </div>

                        <!-- Satuan -->
                        <div class="mb-3">
                            <label class="form-label">Satuan</label>
                            <input type="text" name="satuan" class="form-control" value="<?= $doc->satuan ?? '' ?>">
                        </div>

                        <!-- Biaya -->
                        <div class="mb-3">
                            <label class="form-label">Biaya</label>
                            <input type="text" name="biaya" class="form-control" value="<?= $doc->biaya ?? '' ?>">
                        </div>

                        <!-- PPK -->
                        <div class="mb-3">
                            <label class="form-label">PPK</label>
                            <input type="text" name="nama_ppk" class="form-control" value="<?= $doc->nama_ppk ?? '' ?>" disabled>
                        </div>

                        <!-- NIP PPK -->
                        <div class="mb-3">
                            <label class="form-label">NIP PPK</label>
                            <input type="text" name="nip_ppk" class="form-control" value="<?= $doc->nip_ppk ?? '' ?>" disabled>
                        </div>

                        <!-- Kepala -->
                        <div class="mb-3">
                            <label class="form-label">Kepala</label>
                            <input type="text" class="form-control" value="<?= isset($kepala) ? $kepala->nama : '' ?>" disabled>
                        </div>

                        <!-- NIP Kepala -->
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

        document.querySelectorAll('select[name="kode_anggaran"]').forEach(function(select){
            select.addEventListener('change', function(){

                const selected = this.options[this.selectedIndex];
                const text = selected.text; // "kode - nama kegiatan"

                // ambil bagian nama setelah "-"
                let akun = text.split(' - ')[1] || '';

                // cari hidden input dalam form yang sama
                const hidden = this.closest('form').querySelector('[name="akun_anggaran"]');

                if(hidden){
                    hidden.value = akun.trim();
                }
            });

        });

    /* ================= CLICK BUTTON ================= */
        document.querySelectorAll('.btn-log').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                document.getElementById('tl_id').value = id;
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

                setTimeout(() => {
                    const modalEl = document.getElementById('tlModal');
                    let modal = bootstrap.Modal.getInstance(modalEl);
                    if (!modal) {
                        modal = new bootstrap.Modal(modalEl);
                    }
                    modal.hide();
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

            // init Select2 hanya sekali
            if (!select2Ready) {
                select2Ready = true;

                $regency.select2({
                    dropdownParent: $('#revisiModal' + id),
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

            // ✅ tampilkan nilai default meskipun regencyId kosong
            /*if (regencyText) {
                $regency.find('option').remove();

                if (regencyId) {
                    const option = new Option(regencyText, regencyId, true, true);
                    $regency.append(option).trigger('change');
                } else {
                    // ✅ value dikosongkan, bukan pakai regencyText
                    const option = new Option(regencyText, '', true, true);
                    $regency.append(option).trigger('change');
                }

                $('#provinsi_' + id).val(province);
                $('#province_id_' + id).val(provinceId);
            }*/


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

</script>