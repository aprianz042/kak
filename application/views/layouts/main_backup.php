<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?></title>

    <!-- CSS dipindah ke sini -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <?php $this->load->view('partials/sidebar'); ?>

    <!-- Content -->
    <div class="content">

        <div class="content-header">
            <h1><?= $page_title ?? '' ?></h1>
            <p><?= $page_subtitle ?? '' ?></p>
        </div>

        <div class="content-body">
            <?= $content ?>
        </div>

    </div>

</div>

</body>
</html>
