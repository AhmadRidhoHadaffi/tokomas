<?php
/**
 * Development-only button showcase (/dev/tombol).
 *
 * @var string $variant
 * @var string $size
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Showcase Tombol - Development</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-screen bg-ivory text-charcoal">
<div class="mx-auto max-w-5xl space-y-10 px-4 py-10 sm:px-6" x-data="{ confirmOpen: false, deleted: false }">
    <header class="space-y-2">
        <p class="text-xs font-semibold uppercase tracking-widest text-crimson">Development only</p>
        <h1 class="font-serif text-3xl font-semibold">Showcase Komponen Tombol</h1>
        <p class="max-w-2xl text-sm text-charcoal/60">
            Halaman ini hanya terdaftar saat <code class="rounded bg-charcoal/5 px-1.5 py-0.5 font-mono text-xs">ENVIRONMENT === 'development'</code>.
            Semua contoh di bawah memakai <code class="rounded bg-charcoal/5 px-1.5 py-0.5 font-mono text-xs">components/ui/button</code>
            dan helper <code class="rounded bg-charcoal/5 px-1.5 py-0.5 font-mono text-xs">button_class()</code>.
        </p>
    </header>

    <section class="space-y-4">
        <h2 class="font-serif text-xl font-semibold">Varian</h2>
        <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-line bg-white p-5">
            <?= ui_button(['content' => 'Primary', 'variant' => 'primary']) ?>
            <?= ui_button(['content' => 'Outline', 'variant' => 'outline']) ?>
            <?= ui_button(['content' => 'Ghost', 'variant' => 'ghost']) ?>
            <?= ui_button(['content' => 'Danger', 'variant' => 'danger', 'icon' => 'trash-2']) ?>
            <?= ui_button(['content' => 'Dark', 'variant' => 'dark']) ?>
            <?= ui_button(['content' => 'Light', 'variant' => 'light']) ?>
            <?= ui_button(['content' => 'WhatsApp', 'variant' => 'whatsapp', 'icon' => 'message-circle']) ?>
            <?= ui_button(['content' => 'Outline Charcoal', 'variant' => 'outlineCharcoal']) ?>
        </div>
        <div class="flex flex-wrap items-center gap-3 rounded-2xl bg-charcoal p-5">
            <?= ui_button(['content' => 'Outline Dark', 'variant' => 'outlineDark']) ?>
            <?= ui_button(['content' => 'Outline Light', 'variant' => 'outlineLight']) ?>
            <?= ui_button(['content' => 'Ghost Dark', 'variant' => 'ghostDark', 'icon' => 'log-out']) ?>
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="font-serif text-xl font-semibold">Ukuran</h2>
        <div class="flex flex-wrap items-end gap-3 rounded-2xl border border-line bg-white p-5">
            <?= ui_button(['content' => 'Small (40px)', 'size' => 'sm']) ?>
            <?= ui_button(['content' => 'Medium (44px)', 'size' => 'md']) ?>
            <?= ui_button(['content' => 'Large (48px)', 'size' => 'lg']) ?>
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="font-serif text-xl font-semibold">Ikon, ikon saja, dan full width</h2>
        <div class="grid gap-4 rounded-2xl border border-line bg-white p-5 sm:grid-cols-2">
            <div class="flex flex-wrap items-center gap-3">
                <?= ui_button(['content' => 'Ikon kiri', 'icon' => 'plus']) ?>
                <?= ui_button(['content' => 'Ikon kanan', 'icon' => 'arrow-right', 'iconPosition' => 'right']) ?>
                <?= ui_button(['icon' => 'bell', 'iconOnly' => true, 'ariaLabel' => 'Notifikasi', 'variant' => 'light']) ?>
                <?= ui_button(['icon' => 'x', 'iconOnly' => true, 'ariaLabel' => 'Tutup', 'variant' => 'ghost']) ?>
            </div>
            <div class="space-y-3">
                <?= ui_button(['content' => 'Full width', 'icon' => 'check', 'fullWidth' => true]) ?>
                <?= ui_button(['content' => 'Link internal', 'href' => base_url('masuk'), 'variant' => 'outline', 'fullWidth' => true, 'icon' => 'arrow-left']) ?>
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="font-serif text-xl font-semibold">State: disabled, loading, link eksternal</h2>
        <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-line bg-white p-5">
            <?= ui_button(['content' => 'Disabled', 'disabled' => true]) ?>
            <?= ui_button(['content' => 'Menyimpan', 'loading' => true]) ?>
            <?= ui_button(['content' => 'Disabled link', 'href' => base_url('masuk'), 'disabled' => true, 'variant' => 'outline']) ?>
            <?= ui_button(['content' => 'Eksternal', 'href' => 'https://example.com', 'target' => '_blank', 'variant' => 'light', 'icon' => 'arrow-right', 'iconPosition' => 'right']) ?>
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="font-serif text-xl font-semibold">Konfirmasi aksi berbahaya (pola)</h2>
        <div class="rounded-2xl border border-crimson/20 bg-white p-5">
            <p class="text-sm text-charcoal/65">
                Aplikasi ini belum mengekspos aksi hapus di UI. Pola yang wajib dipakai bila nanti ditambahkan:
                tombol <em>danger</em> → modal konfirmasi Alpine → form <code class="font-mono text-xs">method="post"</code>
                + <code class="font-mono text-xs">csrf_field()</code> (atau <code class="font-mono text-xs">DELETE</code>).
            </p>
            <div class="mt-4">
                <?= ui_button([
                    'content' => 'Hapus data (contoh)',
                    'variant' => 'danger',
                    'icon' => 'trash-2',
                    'attrs' => ['@click' => 'confirmOpen = true'],
                ]) ?>
            </div>
        </div>
    </section>

    <div x-show="confirmOpen" x-cloak @keydown.escape.window="confirmOpen = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-charcoal/60" @click="confirmOpen = false"></div>
        <div role="dialog" aria-modal="true" aria-labelledby="dev-confirm-title" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <h2 id="dev-confirm-title" class="font-serif text-xl font-semibold">Hapus data ini?</h2>
            <p class="mt-2 text-sm text-charcoal/60">Contoh tampilan saja. Tidak ada data yang benar-benar dihapus di halaman development ini.</p>
            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <?= ui_button([
                    'content' => 'Batal',
                    'variant' => 'light',
                    'attrs' => ['@click' => 'confirmOpen = false'],
                ]) ?>
                <?= ui_button([
                    'content' => 'Ya, hapus',
                    'variant' => 'danger',
                    'attrs' => ['@click' => 'confirmOpen = false; deleted = true'],
                ]) ?>
            </div>
        </div>
    </div>

    <p x-show="deleted" x-cloak class="rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-inset ring-emerald-200" role="status">
        Contoh konfirmasi selesai. Tidak ada data yang dihapus.
    </p>
</div>
</body>
</html>
