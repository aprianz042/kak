<?php if ($this->session->flashdata('success')): ?>
    <div class="success-alert">
        <?= $this->session->flashdata('success') ?>
        <br>
        <a href="<?= base_url('docxgenerator/download/'.$this->session->flashdata('file')) ?>">
            Download Dokumen
        </a>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('docxgenerator/generate') ?>">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" required>
    </div>

    <div class="form-group">
        <label>NIP</label>
        <input type="text" name="nip" required>
    </div>

    <div class="form-group">
        <label>Instansi</label>
        <input type="text" name="instansi" required>
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi"></textarea>
    </div>

    <button class="btn-submit" type="submit">Generate DOCX</button>
</form>
