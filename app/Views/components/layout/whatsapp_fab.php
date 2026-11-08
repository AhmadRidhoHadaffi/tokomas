<?php
/**
 * Floating WhatsApp Button (Mobile)
 *
 * @var string $href WhatsApp chat URL
 */

$href = $href ?? 'https://wa.me/6281266778899';
?>
<a
    href="<?= esc($href, 'attr') ?>"
    target="_blank"
    rel="noreferrer"
    aria-label="Chat dengan kami via WhatsApp"
    class="fixed bottom-5 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-wa text-white shadow-lg shadow-black/25 transition-transform duration-150 ease-out hover:scale-105 active:scale-95 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-wa/40 md:hidden"
>
    <?= icon('message-circle', 'h-7 w-7', 28, 2) ?>
</a>
