<header class="flex items-center justify-between border-b border-line bg-white px-6 py-4">
    <div><p class="font-serif text-lg font-semibold text-gold-deep">Toko Mas Gembira</p><p class="text-xs text-charcoal/50">Sistem Informasi Toko</p></div>
    <?php if (session('user_id')): ?><div class="flex items-center gap-4 text-sm"><span><?= esc(session('user_name')) ?></span><a class="text-crimson hover:underline" href="<?= site_url('keluar') ?>">Keluar</a></div><?php endif ?>
</header>
