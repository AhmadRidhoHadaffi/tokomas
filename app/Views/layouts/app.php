<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Toko Mas Gembira') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <style>[x-cloak] { display: none !important; }</style>
    <script>document.documentElement.classList.add('js');</script>
    <?= $this->renderSection('head') ?>
</head>
<body class="min-h-screen">
    <?= $this->renderSection('body') ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
