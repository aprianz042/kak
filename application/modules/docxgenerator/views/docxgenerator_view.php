<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate DOCX</title>
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
            backdrop-filter: blur(10px);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #667eea;
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .header p {
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

        .download-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #667eea;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .download-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button[type="submit"] {
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

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📄 DOCX Generator</h1>
        <p>Buat dokumen profesional dengan mudah</p>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="success-alert">
            <strong>✓ Berhasil!</strong>
            <p style="margin-top: 5px; font-size: 14px;">
                <?= $this->session->flashdata('success'); ?>
            </p>
            <a href="<?= site_url('docx/download/' . $this->session->flashdata('file')) ?>" class="download-link">
                <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489c.075.3.037.617-.1.882a1.5 1.5 0 01-1.302.629H8.5a1.5 1.5 0 01-1.302-.629 1.125 1.125 0 01-.1-.882L7.22 15H5a2 2 0 01-2-2V5zm5.56 8.56l-.385 1.54A.125.125 0 008.5 16h3a.125.125 0 00.124-.1l-.385-1.54a.5.5 0 00-.485-.36h-2.27a.5.5 0 00-.485.36z"/>
                </svg>
                Download Dokumen
            </a>
        </div>
        <script>
            // Hapus alert bawaan, diganti dengan notifikasi yang lebih bagus
        </script>
    <?php endif; ?>

    <form method="post" action="<?= site_url('docx/generate') ?>">
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

        <button type="submit">
            Generate DOCX
        </button>
    </form>
</div>

</body>
</html>