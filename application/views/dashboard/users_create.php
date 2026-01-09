<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pegawai Baru</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #667eea;
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .success-alert {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .error-alert {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>👤 Input Pegawai Baru</h1>
        <p>Tambahkan data pegawai ke sistem</p>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="success-alert">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="error-alert">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('dashboard/users_store') ?>">

        <div class="form-group">
            <label>NIP *</label>
            <input type="text" name="nip" required placeholder="Masukkan NIP Pegawai">
        </div>

        <div class="form-group">
            <label>Nama Lengkap *</label>
            <input type="text" name="nama" required placeholder="Masukkan nama lengkap">
        </div>

        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="pass" required placeholder="Password awal pegawai">
        </div>

        <div class="form-group">
            <label>Role *</label>
            <select name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>

        <button type="submit">
            Simpan Pegawai
        </button>

    </form>
</div>

</body>
</html>
