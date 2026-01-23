<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/pavicon.png') ?>">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>

    <div class="container-fluid">
        <div class="row min-vh-100">

            <!-- Sidebar -->
            <nav class="col-12 col-md-3 col-lg-2 bg-light border-end p-3">
                <?php $this->load->view('partials/sidebar'); ?>
            </nav>

            <!-- Content -->
            <main class="col-12 col-md-9 col-lg-10 p-4">

                <header class="mb-4 border-bottom pb-2">
                    <h1 class="h3 mb-1"><?= $page_title ?? '' ?></h1>
                    <p class="text-muted mb-0"><?= $page_subtitle ?? '' ?></p>
                </header>

                <section>
                    <?= $content ?>
                </section>

            </main>

        </div>
    </div>

<!-- Bootstrap JS (opsional, untuk komponen interaktif) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>
</html>
