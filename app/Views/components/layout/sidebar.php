<aside class="hidden w-64 shrink-0 bg-sidebar text-ivory lg:flex lg:flex-col">
    <div class="border-b border-white/10 p-6"><a href="<?= site_url($role === 'owner' ? 'owner' : 'karyawan') ?>" class="font-serif text-xl font-semibold text-gold">Toko Mas Gembira</a><p class="mt-1 text-xs capitalize text-ivory/50">Panel <?= esc($role) ?></p></div>
    <nav class="flex-1 space-y-1 overflow-y-auto p-4 scrollbar-thin">
        <?php foreach ($items as $item): $active = trim(uri_string(), '/') === trim(parse_url($item['url'], PHP_URL_PATH), '/'); ?>
            <a href="<?= esc($item['url']) ?>" class="block rounded-lg px-4 py-2.5 text-sm transition <?= $active ? 'bg-gold text-charcoal font-semibold' : 'text-ivory/75 hover:bg-white/10 hover:text-ivory' ?>"><?= esc($item['label']) ?></a>
        <?php endforeach ?>
    </nav>
</aside>
