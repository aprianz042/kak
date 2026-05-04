<div class="row">
    <div class="col-md-6 col-12 scroll-col">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5 mb-0">Timeline Dokumen</h3>
        </div>

        <div id="message" class="mt-3 d-none"></div>


        <div id="tl_alert" class="alert d-none mt-2"></div>

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
                    <?php 
                    $role = $this->session->userdata('role');
                    if ($role == 'operator' && $i === $lastIndex && $d->status === 'revisi'): ?>
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

        <?php 
        $role = $this->session->userdata('role');
        $lastTimeline = $timeline[$lastIndex] ?? null;
        $showFormTL = (
            $role == 'ppk' && 
            $lastTimeline && 
            in_array($lastTimeline->status, ['ajuan_baru', 'ajuan_revisi'])
        );
        ?>

        <?php if ($showFormTL): ?>
            <form id="formTL">
                <input type="hidden" name="id_dokumen" value="<?= $id_dokumen ?>" id="tl_id">

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
        <?php endif; ?>
    </div>

    <?php
    function format_text_list($text) {
        $text = trim($text ?? '');

        if (!$text) return '-';

        if (strpos($text, "\n") !== false) {
            $lines = array_filter(array_map('trim', explode("\n", $text)));
            $html = '<ol class="mb-0">';
            foreach ($lines as $line) {
                $html .= '<li>' . htmlspecialchars($line) . '</li>';
            }
            $html .= '</ol>';
            return $html;
        }

        return htmlspecialchars($text);
    }
    ?>


    <div class="col-md-6 col-12 scroll-col">
        <?php foreach ($documents as $doc): ?>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Dokumen</h5>
            </div>

            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Unit Organisasi</div>
                    <div class="col-md-8"><?= $doc->unit_organisasi ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Program</div>
                    <div class="col-md-8"><?= $doc->program ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Kegiatan</div>
                    <div class="col-md-8"><?= $doc->kegiatan ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">KRO</div>
                    <div class="col-md-8"><?= $doc->kro ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">RO</div>
                    <div class="col-md-8"><?= $doc->ro ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Komponen</div>
                    <div class="col-md-8"><?= $doc->komponen ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Kode Anggaran</div>
                    <div class="col-md-8"><?= $doc->kode_anggaran ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Akun Anggaran</div>
                    <div class="col-md-8"><?= $doc->akun_anggaran ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Kab/Kota</div>
                    <div class="col-md-8"><?= $doc->kota_kegiatan ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Provinsi</div>
                    <div class="col-md-8"><?= $doc->provinsi ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Tahun Anggaran</div>
                    <div class="col-md-8"><?= $doc->tahun_anggaran ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Dasar Hukum</div>
                    <div class="col-md-8"><?= format_text_list($doc->dasar_hukum) ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Gambaran Umum</div>
                    <div class="col-md-8"><?= format_text_list($doc->gambaran_umum) ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Maksud & Tujuan</div>
                    <div class="col-md-8"><?= format_text_list($doc->maksud_tujuan) ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Keluaran</div>
                    <div class="col-md-8"><?= format_text_list($doc->keluaran) ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Nama Kegiatan</div>
                    <div class="col-md-8"><?= $doc->nama_kegiatan ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Waktu</div>
                    <div class="col-md-8"><?= $doc->waktu ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Tanggal Bayar</div>
                    <div class="col-md-8"><?= $doc->tanggal_bayar ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Lokasi</div>
                    <div class="col-md-8"><?= $doc->lokasi ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Volume</div>
                    <div class="col-md-8"><?= $doc->vol ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Satuan</div>
                    <div class="col-md-8"><?= $doc->satuan ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Biaya</div>
                    <div class="col-md-8"><?= $doc->biaya ?? '-' ?></div>
                </div>

                <hr>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">PPK</div>
                    <div class="col-md-8"><?= $doc->nama_ppk ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">NIP PPK</div>
                    <div class="col-md-8"><?= $doc->nip_ppk ?? '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Kepala</div>
                    <div class="col-md-8"><?= isset($kepala) ? $kepala->nama : '-' ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">NIP Kepala</div>
                    <div class="col-md-8"><?= isset($kepala) ? $kepala->nip : '-' ?></div>
                </div>

            </div>
        </div>

        <?php endforeach; ?>
    </div>
</div>


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

    /* ================= KODE ANGGARAN ================= */
        document.querySelectorAll('select[name="kode_anggaran"]').forEach(function(select){
            select.addEventListener('change', function(){
                const selected = this.options[this.selectedIndex];
                const text = selected.text;
                let akun = text.split(' - ')[1] || '';
                const hidden = this.closest('form').querySelector('[name="akun_anggaran"]');
                if (hidden) hidden.value = akun.trim();
            });
        });

    /* ================= CLICK BUTTON ================= */
        document.querySelectorAll('.btn-log').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const tlId     = document.getElementById('tl_id');
                const formTL   = document.getElementById('formTL');
                const wrapPesan = document.getElementById('wrap_pesan');

                if (tlId) tlId.value = id;
                if (formTL) formTL.reset();
                if (wrapPesan) wrapPesan.classList.add('d-none');

                loadLog(id);
            });
        });

    /* ================= STATUS CHANGE ================= */
        const tlStatus = document.getElementById('tl_status');
        if (tlStatus) {
            tlStatus.addEventListener('change', function(){
                const wrap = document.getElementById('wrap_pesan');
                if (this.value === 'revisi') {
                    wrap.classList.remove('d-none');
                } else {
                    wrap.classList.add('d-none');
                }
            });
        }

    /* ================= SUBMIT ================= */
        const formTL = document.getElementById('formTL');
        if (formTL) {
            formTL.addEventListener('submit', function(e){
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const alertBox = document.getElementById('tl_alert');

                function showAlert(type, message){
                    if (alertBox) {
                        alertBox.classList.remove('d-none','alert-success','alert-danger');
                        alertBox.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
                        alertBox.innerText = message;
                    } else {
                        alert(message);
                    }
                }

                fetch("<?= base_url('docxgenerator/tindak_lanjut_ppk') ?>", {
                    method: 'POST',
                    body: formData
                })
                .then(res => {
                    return res.text().then(text => {
                        console.log('RAW RESPONSE:', text);
                        try {
                            return JSON.parse(text);
                        } catch(e) {
                            throw new Error('Response bukan JSON: ' + text);
                        }
                    });
                })
                .then(res => {
                    if (res.status) {
                        showAlert('success', 'Berhasil diproses');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        showAlert('error', res.message || 'Gagal diproses');
                    }
                })
                .catch(err => {
                    console.error('ERROR:', err.message);
                    showAlert('error', err.message);
                });
            });
        }

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

                    if (!select2Ready) {
                        select2Ready = true;

                        $regency.select2({
                        dropdownParent: document.getElementById('revisiModal' + id), // ✅ pakai native element
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

}); // ✅ tutup DOMContentLoaded
</script>
