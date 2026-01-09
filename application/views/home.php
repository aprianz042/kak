<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - 2 Panel Layout</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
        }
        
        .container {
            display: flex;
            height: 100vh;
        }
        
        /* Panel Kiri - Sidebar Menu */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .sidebar-header h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .menu {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }
        
        .menu-item {
            padding: 15px 20px;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: white;
        }
        
        .menu-item.active {
            background: rgba(255,255,255,0.2);
            border-left-color: white;
        }
        
        .menu-icon {
            font-size: 20px;
            width: 24px;
        }
        
        .menu-text {
            font-size: 15px;
        }
        
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-weight: bold;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-info h4 {
            font-size: 14px;
            margin-bottom: 2px;
        }
        
        .user-info p {
            font-size: 11px;
            opacity: 0.8;
        }
        
        /* Panel Kanan - Content Area */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f5f5f5;
        }
        
        .content-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .content-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .content-header p {
            color: #666;
            font-size: 14px;
        }
        
        .content-body {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #667eea;
        }
        
        .stat-card h4 {
            color: #999;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .stat-card .value {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        /* Form Styles */
        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 600px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-header h2 {
            color: #667eea;
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-header p {
            color: #666;
            font-size: 14px;
        }

        .success-alert {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }
        
        /* Scrollbar Styling */
        .menu::-webkit-scrollbar,
        .content-body::-webkit-scrollbar {
            width: 6px;
        }
        
        .menu::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        .menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .content-body::-webkit-scrollbar-track {
            background: #f5f5f5;
        }
        
        .content-body::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            
            .sidebar-header h2,
            .sidebar-header p,
            .menu-text,
            .user-info {
                display: none;
            }
            
            .menu-item {
                justify-content: center;
                padding: 15px 10px;
            }
            
            .user-profile {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Panel Kiri - Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Dashboard</h2>
                <p>Admin Panel</p>
            </div>
            
            <div class="menu">
                <div class="menu-item active" onclick="showContent('dashboard')">
                    <span class="menu-icon">🏠</span>
                    <span class="menu-text">Dashboard</span>
                </div>
                <div class="menu-item" onclick="showContent('docx')">
                    <span class="menu-icon">📄</span>
                    <span class="menu-text">DOCX Generator</span>
                </div>
                <div class="menu-item" onclick="showContent('users')">
                    <span class="menu-icon">👥</span>
                    <span class="menu-text">Users</span>
                </div>
                <div class="menu-item" onclick="showContent('products')">
                    <span class="menu-icon">📦</span>
                    <span class="menu-text">Products</span>
                </div>
                <div class="menu-item" onclick="showContent('orders')">
                    <span class="menu-icon">🛒</span>
                    <span class="menu-text">Orders</span>
                </div>
                <div class="menu-item" onclick="showContent('settings')">
                    <span class="menu-icon">⚙️</span>
                    <span class="menu-text">Settings</span>
                </div>
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
        
        <!-- Panel Kanan - Content -->
        <div class="content">
            <div class="content-header">
                <h1 id="pageTitle">Dashboard</h1>
                <p id="pageSubtitle">Selamat datang di admin panel</p>
            </div>
            
            <div class="content-body" id="contentBody">
                <!-- Dashboard Content -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h4>Total Users</h4>
                        <div class="value">1,234</div>
                    </div>
                    <div class="stat-card">
                        <h4>Total Products</h4>
                        <div class="value">567</div>
                    </div>
                    <div class="stat-card">
                        <h4>Total Orders</h4>
                        <div class="value">890</div>
                    </div>
                    <div class="stat-card">
                        <h4>Revenue</h4>
                        <div class="value">$45.2K</div>
                    </div>
                </div>
                
                <div class="card">
                    <h3>Selamat Datang!</h3>
                    <p>Ini adalah panel admin dengan layout 2 kolom. Panel kiri berisi menu navigasi dan panel kanan berisi konten utama.</p>
                    <p>Klik menu di sebelah kiri untuk melihat konten yang berbeda.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showContent(page) {
            // Update active menu
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => item.classList.remove('active'));
            event.currentTarget.classList.add('active');
            
            // Content untuk setiap menu
            const contents = {
                dashboard: {
                    title: 'Dashboard',
                    subtitle: 'Selamat datang di admin panel',
                    body: `
                        <div class="stats-grid">
                            <div class="stat-card">
                                <h4>Total Users</h4>
                                <div class="value">1,234</div>
                            </div>
                            <div class="stat-card">
                                <h4>Total Products</h4>
                                <div class="value">567</div>
                            </div>
                            <div class="stat-card">
                                <h4>Total Orders</h4>
                                <div class="value">890</div>
                            </div>
                            <div class="stat-card">
                                <h4>Revenue</h4>
                                <div class="value">$45.2K</div>
                            </div>
                        </div>
                        <div class="card">
                            <h3>Selamat Datang!</h3>
                            <p>Ini adalah panel admin dengan layout 2 kolom.</p>
                        </div>
                    `
                },
                docx: {
                    title: 'DOCX Generator',
                    subtitle: 'Buat dokumen profesional dengan mudah',
                    body: `
                        <div class="form-container">
                            <div class="form-header">
                                <h2>📄 DOCX Generator</h2>
                                <p>Buat dokumen profesional dengan mudah</p>
                            </div>

                            <form method="post" action="#" id="docxForm">
                                <div class="form-group">
                                    <label for="nama">Nama Lengkap *</label>
                                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                                </div>

                                <div class="form-group">
                                    <label for="nip">NIP *</label>
                                    <input type="text" id="nip" name="nip" placeholder="Masukkan NIP" required>
                                </div>

                                <div class="form-group">
                                    <label for="instansi">Instansi</label>
                                    <input type="text" id="instansi" name="instansi" placeholder="Masukkan nama instansi">
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi atau keterangan tambahan"></textarea>
                                </div>

                                <button type="submit" class="btn-submit">
                                    Generate DOCX
                                </button>
                            </form>
                        </div>
                    `
                },
                users: {
                    title: 'Users Management',
                    subtitle: 'Kelola pengguna sistem',
                    body: `
                        <div class="card">
                            <h3>Daftar Users</h3>
                            <p>• John Doe - john@email.com - Admin</p>
                            <p>• Jane Smith - jane@email.com - User</p>
                            <p>• Bob Johnson - bob@email.com - User</p>
                            <p>• Alice Williams - alice@email.com - Moderator</p>
                        </div>
                    `
                },
                products: {
                    title: 'Products',
                    subtitle: 'Kelola produk Anda',
                    body: `
                        <div class="card">
                            <h3>Daftar Produk</h3>
                            <p>• Laptop Gaming - Rp 15.000.000 - Stok: 25</p>
                            <p>• Mouse Wireless - Rp 250.000 - Stok: 100</p>
                            <p>• Keyboard Mechanical - Rp 1.200.000 - Stok: 50</p>
                            <p>• Monitor 27" - Rp 3.500.000 - Stok: 30</p>
                        </div>
                    `
                },
                orders: {
                    title: 'Orders',
                    subtitle: 'Kelola pesanan pelanggan',
                    body: `
                        <div class="card">
                            <h3>Pesanan Terbaru</h3>
                            <p>• Order #1001 - Rp 15.500.000 - Pending</p>
                            <p>• Order #1002 - Rp 3.750.000 - Processing</p>
                            <p>• Order #1003 - Rp 1.450.000 - Completed</p>
                            <p>• Order #1004 - Rp 6.200.000 - Shipped</p>
                        </div>
                    `
                },
                settings: {
                    title: 'Settings',
                    subtitle: 'Pengaturan sistem',
                    body: `
                        <div class="card">
                            <h3>Pengaturan Umum</h3>
                            <p>• Nama Website: MyStore Admin</p>
                            <p>• Email: admin@mystore.com</p>
                            <p>• Timezone: Asia/Jakarta (WIB)</p>
                            <p>• Currency: IDR (Rupiah)</p>
                        </div>
                    `
                }
            };
            
            // Update content
            document.getElementById('pageTitle').textContent = contents[page].title;
            document.getElementById('pageSubtitle').textContent = contents[page].subtitle;
            document.getElementById('contentBody').innerHTML = contents[page].body;

            // Attach form submit handler untuk DOCX form
            if (page === 'docx') {
                document.getElementById('docxForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Form DOCX Generator disubmit!\n\nDalam implementasi CodeIgniter, form ini akan dikirim ke controller untuk generate DOCX.');
                });
            }
        }
    </script>
</body>
</html>