<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
        <hr class="my-2">
        <a class="btn btn-sm btn-success"
        href="<?= base_url('docxgenerator/download/'.$this->session->flashdata('file')) ?>">
        Download Dokumen
    </a>
</div>
<?php endif; ?>

<div class="row">
    <!-- Kolom kiri -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <h4 class="card-title mb-3">Generate Dokumen DOCX</h4>

                <form method="post" action="<?= base_url('docxgenerator/generate') ?>" class="needs-validation" novalidate>

                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control"
                        placeholder="Masukkan nama lengkap" required>
                        <div class="invalid-feedback">Nama wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control"
                        placeholder="Masukkan NIP" required>
                        <div class="invalid-feedback">NIP wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Instansi <span class="text-danger">*</span></label>
                        <input type="text" name="instansi" class="form-control"
                        placeholder="Nama instansi" required>
                        <div class="invalid-feedback">Instansi wajib diisi.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-control"
                        placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Rincian Kegiatan</label>
                        <textarea name="rincian" rows="5" class="form-control"
                        placeholder="Satu baris = satu rincian"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Generate DOCX
                    </button>

                </form>

            </div>
        </div>
    </div>

    <!-- Kolom kanan -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3">Preview Dokumen</h4>

                <div id="docPreview" class="border p-3 bg-light" style="min-height:300px">
                    <em>Preview akan muncul di sini...</em>
                </div>
            </div>
        </div>

    </div>
</div>


<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
    
    function updatePreview() {
        const nama = document.querySelector('[name="nama"]').value;
        const nip = document.querySelector('[name="nip"]').value;
        const instansi = document.querySelector('[name="instansi"]').value;
        const deskripsi = document.querySelector('[name="deskripsi"]').value;
        const rincian = document.querySelector('[name="rincian"]').value
        .split('\n')
        .filter(r => r.trim() !== '')
        .map(r => `<li>${r}</li>`)
        .join('');

        document.getElementById('docPreview').innerHTML = `
        <h5 style="text-align:center">SURAT KETERANGAN</h5>
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

        <table class="table table-sm">
            <tr><td>Nama</td><td>: ${nama}</td></tr>
            <tr><td>NIP</td><td>: ${nip}</td></tr>
            <tr><td>Instansi</td><td>: ${instansi}</td></tr>
        </table>

        <p>${deskripsi}</p>

        <ul>${rincian}</ul>
        `;
    }

    document.querySelectorAll('input, textarea').forEach(el => {
        el.addEventListener('input', updatePreview);
    });
</script>
