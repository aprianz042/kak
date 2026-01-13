<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem</title>

    <!-- CSS kamu TETAP -->
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            display:flex; justify-content:center; align-items:center;
            min-height:100vh; padding:20px;
        }
        .login-container {
            background:white; border-radius:20px;
            box-shadow:0 20px 60px rgba(0,0,0,.3);
            overflow:hidden; width:100%; max-width:400px;
        }
        .login-header {
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            padding:40px 30px; text-align:center; color:white;
        }
        .login-body { padding:40px 30px; }
        .form-group { margin-bottom:25px; }
        .form-group label { margin-bottom:8px; display:block; }
        .form-group input {
            width:100%; padding:12px 15px;
            border:2px solid #e0e0e0; border-radius:8px;
        }
        .btn-login {
            width:100%; padding:14px;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            color:white; border:none; border-radius:8px;
            font-size:16px; cursor:pointer;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h1>Selamat Datang</h1>
        <p>Silakan login untuk melanjutkan</p>
    </div>

    <div class="login-body">

        <!-- ALERT ERROR DARI CI -->
        <?php if ($this->session->flashdata('error')): ?>
            <script>
                alert("<?= $this->session->flashdata('error'); ?>");
            </script>
        <?php endif; ?>

        <!-- FORM LOGIN (NIP + PASSWORD) -->
        <form method="post" action="<?= site_url('auth/do_login') ?>">
            <div class="form-group">
                <label>NIP</label>
                <input type="text" name="nip" required placeholder="Masukkan NIP Pegawai">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="pass" required placeholder="Masukkan Password">
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

    </div>
</div>

</body>
</html>
