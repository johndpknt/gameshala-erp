<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= esc($pageTitle ?? 'Gameshala ERP') ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= asset_url('favicon.ico') ?>">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Custom overrides on top of Bootstrap -->
    <link rel="stylesheet" href="<?= asset_url('assets/css/layout.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/pages.css') ?>">
    <?= $extraHead ?? '' ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?= view('layout/header') ?>
<div class="flex-grow-1">
    <?php if (session('message')): ?>
        <div class="container px-3 px-sm-4 mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session('message')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session('error')): ?>
        <div class="container px-3 px-sm-4 mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session('errors')): ?>
        <div class="container px-3 px-sm-4 mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
<?= $content ?>
</div>
<?= view('layout/footer') ?>
