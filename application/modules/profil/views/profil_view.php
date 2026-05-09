
<div class="container py-3">

    <!-- Flash Message -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <div class="row g-3">

        <!-- Sidebar -->
        <div class="col-12 col-md-3">

            <div class="card text-center">
                <div class="card-body">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width:70px;height:70px;">
                        <?= strtoupper(substr($profil->nama,0,2)) ?>
                    </div>

                    <h6 class="mb-0"><?= $profil->nama ?></h6>
                    <small class="text-muted"><?= $profil->jabatan ?></small><br>
                    <span class="badge bg-primary mt-2"><?= $profil->role ?></span>

                    <hr>

                    <div class="text-start small">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">NIP</span>
                            <span><?= $profil->nip ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Bergabung</span>
                            <span><?= date('d M Y', strtotime($profil->created_at)) ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status TTD</span>
                            <?php if (!empty($profil->ttd)): ?>
                                <span class="text-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>Sudah
                                </span>
                            <?php else: ?>
                                <span class="text-danger">
                                    <i class="bi bi-x-circle-fill me-1"></i>Belum
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <div class="card mt-3">
                <div class="list-group list-group-flush">
                    <a href="#data-pribadi" class="list-group-item list-group-item-action active" onclick="setActive(this)">Data Pribadi</a>
                    <a href="#ubah-password" class="list-group-item list-group-item-action" onclick="setActive(this)">Ubah Password</a>
                    <a href="#tanda-tangan" class="list-group-item list-group-item-action" onclick="setActive(this)">Tanda Tangan</a>
                </div>
            </div>

        </div>

        <!-- MAIN -->
        <div class="col-12 col-md-9">

            <!-- ================= DATA PROFIL ================= -->
            <form action="<?= base_url('profil/update_profil') ?>" method="post">
                <div class="card mb-3" id="data-pribadi">
                    <div class="card-header"><b>Data Pribadi</b></div>
                    <div class="card-body">

                        <div class="mb-2">
                            <label>NIP</label>
                            <input type="text" class="form-control" value="<?= $profil->nip ?>" readonly>
                        </div>

                        <div class="mb-2">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?= $profil->nama ?>">
                        </div>

                        <div class="mb-2">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $profil->email ?>">
                        </div>

                        <button class="btn btn-primary mt-2">Simpan</button>
                    </div>
                </div>
            </form>

            <!-- ================= PASSWORD ================= -->
            <form action="<?= base_url('profil/update_password') ?>" method="post">
                <div class="card mb-3" id="ubah-password">
                    <div class="card-header"><b>Ubah Password</b></div>
                    <div class="card-body">

                        <input type="password" name="current_password" class="form-control mb-2" placeholder="Password Lama">
                        <input type="password" name="new_password" class="form-control mb-2" placeholder="Password Baru">
                        <input type="password" name="confirm_password" class="form-control mb-2" placeholder="Konfirmasi Password">

                        <button class="btn btn-primary">Update Password</button>
                    </div>
                </div>
            </form>

            <!-- ================= TTD ================= -->
            <form action="<?= base_url('profil/upload_ttd') ?>" method="post" enctype="multipart/form-data">
                <div class="card" id="tanda-tangan">
                    <div class="card-header"><b>Tanda Tangan</b></div>
                    <div class="card-body">

                        <div class="d-flex align-items-center gap-3 mb-3">

                            <?php if (!empty($profil->ttd)): ?>
                                <img src="<?= base_url('storage/ttd/'.$profil->ttd) ?>"
                                style="width:80px;height:80px;object-fit:contain;">
                            <?php else: ?>
                                <span class="text-muted">Belum ada tanda tangan</span>
                            <?php endif; ?>

                            <?php if (!empty($profil->ttd)): ?>
                                <a href="<?= base_url('profil/hapus_ttd') ?>"
                                 class="btn btn-danger btn-sm"
                                 onclick="return confirm('Hapus TTD?')">
                                 Hapus
                             </a>
                         <?php endif; ?>

                     </div>

                     <input type="file" name="ttd" id="ttdInput" class="form-control mb-2">
                     <input type="hidden" name="ttd_base64" id="ttd_base64">

                     <button class="btn btn-primary">Upload</button>
                 </div>
             </div>
         </form>

     </div>
 </div>
</div>

<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Crop Tanda Tangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body text-center">
            <img id="cropImage" style="max-width:100%;">
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="cropBtn">Crop & Upload</button>
        </div>

    </div>
</div>
</div>

<link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let cropper;
    const input = document.getElementById('ttdInput');
    const image = document.getElementById('cropImage');
    const modalEl = document.getElementById('cropModal');
    const modal = new bootstrap.Modal(modalEl);

// ================= INIT FILE =================
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = async function(e) {
            const resized = await resizeImage(e.target.result);

            image.src = resized;
            modal.show();
        };
        reader.readAsDataURL(file);
    });

// ================= INIT CROPPER (SETELAH MODAL MUNCUL) =================
    modalEl.addEventListener('shown.bs.modal', function () {

        if (cropper) {
            cropper.destroy();
        }

        cropper = new Cropper(image, {
            viewMode: 1,
            autoCropArea: 1,
            responsive: true,

            ready() {
                const container = cropper.getContainerData();

        // 🔥 paksa width max 80% dari modal
                const maxWidth = document.querySelector('#cropModal .modal-body').clientWidth * 0.8;

                let newWidth = container.width;
                let newHeight = container.height;

                if (newWidth > maxWidth) {
                    const scale = maxWidth / newWidth;
                    newWidth = maxWidth;
                    newHeight = newHeight * scale;
                }

                cropper.setCanvasData({
                    width: newWidth,
                    height: newHeight
                });
            }
        });

    // paksa supaya langsung besar & center
        setTimeout(() => {
            cropper.resize();
            /*cropper.zoomTo(1);*/
            cropper.reset(); // lebih stabil daripada zoomTo
        }, 100);
    });



// ================= CROP BUTTON =================
    document.getElementById('cropBtn').addEventListener('click', function() {

        let canvas = cropper.getCroppedCanvas({
            height: 150
        });

    // remove background
        canvas = removeWhiteBackground(canvas);

        const base64 = canvas.toDataURL('image/png');

        document.getElementById('ttd_base64').value = base64;

        modal.hide();

        input.closest('form').submit();
    });


    function resizeImage(base64, maxWidth = 800) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = function () {

                let width = img.width;
                let height = img.height;

            // kalau terlalu lebar → scale down
                if (width > maxWidth) {
                    const scale = maxWidth / width;
                    width = maxWidth;
                    height = height * scale;
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                resolve(canvas.toDataURL('image/png'));
            };
            img.src = base64;
        });
    }
// ================= REMOVE BACKGROUND =================
    function removeWhiteBackground(canvas) {
        const ctx = canvas.getContext('2d');
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const data = imgData.data;

        for (let i = 0; i < data.length; i += 4) {
            const r = data[i];
            const g = data[i + 1];
            const b = data[i + 2];

            if (r > 240 && g > 240 && b > 240) {
                data[i + 3] = 0;
            }
        }

        ctx.putImageData(imgData, 0, 0);
        return canvas;
    }
// Navigasi sidebar aktif
    function setActive(el) {
        document.querySelectorAll('.list-group-item').forEach(item => item.classList.remove('active'));
        el.classList.add('active');
    }

// Toggle show/hide password
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

// Cek kekuatan password
    function checkStrength(val) {
        const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3')];
        const label = document.getElementById('pw-label');
        bars.forEach(b => { b.className = 'pw-bar'; });
        label.style.display = 'block';
        if (val.length === 0) { label.style.display = 'none'; return; }
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score++;
        if (score === 1) {
            bars[0].classList.add('weak');
            label.className = 'pw-strength-label weak'; label.textContent = 'Lemah';
        } else if (score === 2) {
            bars[0].classList.add('medium'); bars[1].classList.add('medium');
            label.className = 'pw-strength-label medium'; label.textContent = 'Sedang';
        } else {
            bars.forEach(b => b.classList.add('strong'));
            label.className = 'pw-strength-label strong'; label.textContent = 'Kuat';
        }
    }

// Cek kecocokan password
    function checkMatch() {
        const newPw = document.getElementById('pw-new').value;
        const confirm = document.getElementById('pw-confirm').value;
        const ml = document.getElementById('match-label');
        if (!confirm) { ml.textContent = ''; return; }
        if (newPw === confirm) {
            ml.style.color = '#10b981';
            ml.innerHTML = '<i class="bi bi-check-circle"></i> Password cocok';
        } else {
            ml.style.color = '#ef4444';
            ml.innerHTML = '<i class="bi bi-x-circle"></i> Password tidak cocok';
        }
    }

// Handle file TTD
    function handleFile(file) {
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2 MB');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('ttd-img');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('ttd-placeholder-text').style.display = 'none';
            document.getElementById('ttd-filename').textContent = file.name;
            document.getElementById('ttd-fileMeta').textContent =
            'Baru diunggah · ' + (file.size / 1024).toFixed(1) + ' KB';
        };
        reader.readAsDataURL(file);
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('ttd-dropzone').classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    }

    function hapusTTD() {
        if (!confirm('Hapus tanda tangan ini?')) return;
        document.getElementById('ttd-img').style.display = 'none';
        document.getElementById('ttd-placeholder-text').style.display = 'block';
        document.getElementById('ttd-filename').textContent = 'Belum ada tanda tangan';
        document.getElementById('ttd-fileMeta').textContent = 'Unggah file di bawah';
        document.getElementById('ttd-input').value = '';
    }
</script>
