<?= $this->extend('layouts/app') ?>

<?= $this->section('body') ?>
<?php
$storeData = $store ?? [];
$waNumber = preg_replace('/\D+/', '', (string) ($storeData['whatsapp'] ?? ''));
$waHref = $waNumber !== '' ? 'https://wa.me/' . $waNumber : 'https://wa.me/6281266778899';
?>
<div class="min-h-screen overflow-x-hidden bg-ivory">
    <?= $this->include('components/layout/public_navbar') ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('components/layout/footer', ['store' => $storeData]) ?>
    <?= $this->include('components/layout/whatsapp_fab', ['href' => $waHref]) ?>
</div>

<script>
    (() => {
        const items = document.querySelectorAll('.reveal');
        if (!items.length) return;

        if (!('IntersectionObserver' in window)) {
            items.forEach((item) => item.classList.add('is-visible'));
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '-60px 0px' });

        items.forEach((item) => observer.observe(item));
    })();
</script>
<?= $this->endSection() ?>
