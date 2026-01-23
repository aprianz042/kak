<?php $active = $this->router->fetch_class(); ?>

<aside class="d-flex flex-column flex-shrink-0 bg-light border-end h-100">
 <!-- LOGO + TITLE -->
 <div class="p-3 border-bottom">
    <div class="d-flex align-items-center gap-2">
        <img 
        src="<?= base_url('assets/images/pavicon.png') ?>" 
        alt="Logo"
        style="width: 36px; height: 36px;"
        class="rounded"
        >
        <div>
            <h2 class="h6 mb-0">Dashboard</h2>
            <p class="text-muted small mb-0">Admin Panel</p>
        </div>
    </div>
</div>

<!-- Menu -->
<nav class="nav nav-pills flex-column mb-auto p-3 gap-1">

    <a href="<?= base_url('home') ?>"
       class="nav-link d-flex align-items-center <?= $active == 'home' ? 'active' : 'link-dark' ?>">
       <span class="me-2">🏠</span>
       <span>Dashboard</span>
   </a>

   <a href="<?= base_url('docxgenerator') ?>"
       class="nav-link d-flex align-items-center <?= $active == 'docxgenerator' ? 'active' : 'link-dark' ?>">
       <span class="me-2">📄</span>
       <span>DOCX Generator</span>
   </a>

   <a href="<?= base_url('pegawai') ?>"
       class="nav-link d-flex align-items-center <?= $active == 'pegawai' ? 'active' : 'link-dark' ?>">
       <span class="me-2">👥</span>
       <span>Pegawai</span>
   </a>

</nav>

<!-- Footer / user profile -->
<div class="mt-auto p-3 border-top">

    <div class="d-flex align-items-center mb-2">
        <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
        style="width: 40px; height: 40px;">
        A
    </div>

    <div class="ms-2">
        <h4 class="h6 mb-0"><?= $this->session->userdata('nama'); ?></h4>
        <p class="text-muted small mb-0"><?= $this->session->userdata('email'); ?></p>
    </div>
</div>

<!-- LOGOUT -->
<a href="<?= base_url('auth/logout') ?>"
   class="btn btn-outline-danger btn-sm w-100">
   Logout
</a>

</div>
</aside>
