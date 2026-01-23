<nav class="main-header navbar navbar-expand navbar-light bg-body shadow-sm border-bottom">
	<div class="container-fluid">

		<!-- Left navbar: brand / toggle sidebar -->
		<button class="btn btn-link d-inline-block d-lg-none me-2" data-lte-toggle="sidebar">
			<i class="bi bi-list"></i> <!-- bisa ganti icon sesuai lib yang dipakai -->
		</button>

		<a href="<?= base_url() ?>" class="navbar-brand fw-semibold">
			<!-- Logo kecil kalau mau -->
			<!-- <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
			<span class="ms-1">Your App</span>
		</a>

		<!-- Right navbar -->
		<ul class="navbar-nav ms-auto">

			<!-- Link biasa -->
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a>
			</li>

			<!-- Dropdown user -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
					<?= $this->session->userdata('username') ?? 'User' ?>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item" href="<?= base_url('profile') ?>">Profil</a></li>
					<li><a class="dropdown-item" href="<?= base_url('settings') ?>">Pengaturan</a></li>
					<li><hr class="dropdown-divider"></li>
					<li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">Logout</a></li>
				</ul>
			</li>

		</ul>
	</div>
</nav>
