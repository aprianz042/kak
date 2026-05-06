
<div class="row g-3 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Selamat Datang!</h4>
                <p class="card-text mb-0">
                <?= $nama ?> | <?= $role ?>
                </p>
        </div>
    </div>
</div>

<div class="d-flex align-items-center gap-3 my-4">
    <hr class="flex-grow-1 m-0">
    <span class="text-muted small fw-semibold">DOKUMEN KAK</span>
    <hr class="flex-grow-1 m-0">
</div>

<div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Draft</h6>
                <h3 class="fw-bold mb-0"><?= $total_draft ?></h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Dalam Proses</h6>
                <h3 class="fw-bold mb-0"><?= $total_lainnya ?></h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Selesai</h6>
                <h3 class="fw-bold mb-0"><?= $total_disetujui ?></h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Ditolak</h6>
                <h3 class="fw-bold mb-0"><?= $total_ditolak ?></h3>
            </div>
        </div>
    </div>

</div>

<div class="d-flex align-items-center gap-3 my-4">
    <hr class="flex-grow-1 m-0">
    <span class="text-muted small fw-semibold">ESTIMASI REALISASI ANGGARAN</span>
    <hr class="flex-grow-1 m-0">
</div>

<div class="row g-3">
    <?php foreach ($anggaran as $row) : ?>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted"><?= $row->kode_akun ?></h6>
                <h6 class="text-muted"><?= $row->nama_kegiatan ?></h6>
                <h6 class="text-muted">Pagu : Rp. <?= number_format($row->pagu, 0, ',', '.') ?></h6>
                <h6 class="text-muted">Realisasi : Rp. <?= number_format($row->realisasi, 0, ',', '.') ?></h6>
                <h3 class="fw-bold mb-0"><?= $row->persentase ?>%</h3>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>