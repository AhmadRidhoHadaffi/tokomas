<!-- ============ REGISTER BANNER ============ -->
<section class="bg-ivory pb-20 lg:pb-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal">
            <div class="relative overflow-hidden rounded-2xl bg-gold-gradient px-6 py-12 sm:px-12 lg:flex lg:items-center lg:justify-between lg:py-14">
                <div class="bg-arabesque absolute inset-0 opacity-50 mix-blend-multiply" aria-hidden="true"></div>
                <div class="relative max-w-xl">
                    <h2 class="font-serif text-3xl font-semibold text-charcoal sm:text-4xl">Bergabung dan Mulai Pesan Custom</h2>
                    <p class="mt-3 text-charcoal/75">Gratis selamanya. Ajukan pesanan custom, pantau pengerjaannya, dan dapatkan promo khusus pelanggan.</p>
                </div>
                <div class="relative mt-8 flex flex-col gap-3 sm:flex-row lg:mt-0">
                    <?= ui_button([
                        'href' => site_url('daftar'),
                        'content' => 'Buat Akun Gratis',
                        'variant' => 'dark',
                        'size' => 'lg',
                    ]) ?>
                    <?= ui_button([
                        'href' => site_url('masuk'),
                        'content' => 'Masuk ke Akun',
                        'variant' => 'outlineCharcoal',
                        'size' => 'lg',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
