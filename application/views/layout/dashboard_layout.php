<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            display: flex;
        }
        .sidebar {
            width: 220px;
            background: #111827;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 4px;
        }
        .sidebar a:hover {
            background: #1f2937;
        }
        .content {
            flex: 1;
            padding: 20px;
            background: #f3f4f6;
        }
        .topbar {
            background: white;
            padding: 10px 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>Dashboard</h3>

    <a href="<?= site_url('dashboard') ?>">Home</a>
    <a href="<?= site_url('dashboard/docx') ?>">DOCX</a>
    <a href="<?= site_url('dashboard/users') ?>">Users</a>
    <a href="<?= site_url('dashboard/products') ?>">Products</a>
    <a href="<?= site_url('dashboard/orders') ?>">Orders</a>
    <a href="<?= site_url('dashboard/settings') ?>">Settings</a>
    <hr>
    <a href="<?= site_url('auth/logout') ?>">Logout</a>
</div>

<div class="content">
    <div class="topbar">
        Login sebagai: <?= $this->session->userdata('nama'); ?>
    </div>

    <?php $this->load->view($content); ?>
</div>

</body>
</html>
