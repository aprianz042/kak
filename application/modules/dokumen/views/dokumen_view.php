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
                <td>
                    <div style="display:flex; justify-content:space-between;">
                        <span>Rp.</span>
                        <span><?= number_format($d->biaya, 0, ',', '.') ?>,-</span>
                    </div>
                </td>
                <td><?= htmlspecialchars($d->nama_ppk) ?></td>

                <td>
                    <button class="btn btn-sm btn-success w-100 mb-2 btn-view" data-bs-toggle="modal" data-bs-target="#viewModal" data-file="<?= base_url('storage/pdf/'.$d->file_doc.'.pdf') ?>"> 
                        <i class="fa fa-eye"></i> Lihat Dokumen
                    </button>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- MODAL VIEW -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-pdf"></i> Preview Dokumen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="pdfViewer" src="" style="border: none;"></iframe>
                </div>
            </div>

            <!-- FOOTER (optional tapi bagus) -->
            <div class="modal-footer">
                <a id="btnDownload" href="<?= base_url('docxgenerator/download_pdf/'.$d->file_doc) ?>" target="_blank" class="btn btn-primary">
                    <i class="bi bi-file-earmark-pdf"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

<script>

    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', function() {
            const file = this.getAttribute('data-file');
            document.getElementById('pdfViewer').src = file;
        });
    });

// optional: reset saat modal ditutup biar gak terus load
    document.getElementById('viewModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('pdfViewer').src = '';
    });

</script>
