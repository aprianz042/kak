<?php $active = $this->router->fetch_class(); ?>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Dashboard</h2>
        <p>Admin Panel</p>
    </div>

    <div class="menu">
        <a href="<?= base_url('home') ?>" class="menu-item <?= $active == 'home' ? 'active' : '' ?>">
            <span class="menu-icon">🏠</span>
            <span class="menu-text">Dashboard</span>
        </a>

        <a href="<?= base_url('docxgenerator') ?>" class="menu-item <?= $active == 'docxgenerator' ? 'active' : '' ?>">
            <span class="menu-icon">📄</span>
            <span class="menu-text">DOCX Generator</span>
        </a>

        <a href="<?= base_url('user') ?>" class="menu-item <?= $active == 'user' ? 'active' : '' ?>">
            <span class="menu-icon">👥</span>
            <span class="menu-text">Users</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">A</div>
            <div class="user-info">
                <h4>Admin User</h4>
                <p>admin@email.com</p>
            </div>
        </div>
    </div>
</div>
