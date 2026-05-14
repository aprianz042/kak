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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body p-0">
                <iframe id="pdfViewer" src="" width="100%" height="600px"></iframe>
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
