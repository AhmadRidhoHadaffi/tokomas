<?php
/**
 * Modal Component (Alpine.js)
 * 
 * Usage in page:
 * <div x-data="{ showModal: false }">
 *     <?= view('components/ui/modal', [
 *         'id' => 'myModal',
 *         'title' => 'Modal Title',
 *         'description' => 'Optional description',
 *         'size' => 'md',
 *         'content' => '<p>Modal body content</p>',
 *         'footer' => '<button>Action</button>',
 *     ]) ?>
 * </div>
 * 
 * @var string $id Modal identifier for x-show binding
 * @var string $title Modal title
 * @var string $description Optional description
 * @var string $size sm|md|lg|xl
 * @var string $content Modal body HTML
 * @var string $footer Optional footer HTML
 */

$id = $id ?? 'modal';
$title = $title ?? '';
$modalTitle = $modalTitle ?? $title;
$description = $description ?? '';
$size = $size ?? 'md';
$content = $content ?? '';
$footer = $footer ?? '';

$sizeClasses = [
    'sm' => 'max-w-md',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
];

$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
?>
<div
    x-show="<?= esc($id, 'attr') ?>"
    x-cloak
    @keydown.escape.window="<?= esc($id, 'attr') ?> = false"
    class="fixed inset-0 z-[60] flex items-end justify-center p-0 sm:items-center sm:p-6"
    style="display: none;"
>
    <!-- Backdrop -->
    <div
        x-show="<?= esc($id, 'attr') ?>"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="<?= esc($id, 'attr') ?> = false"
        class="absolute inset-0 bg-charcoal/60 backdrop-blur-[2px]"
    ></div>

    <!-- Modal Panel -->
    <div
        x-show="<?= esc($id, 'attr') ?>"
        x-transition:enter="transition ease-out duration-220"
        x-transition:enter-start="opacity-0 scale-96 translate-y-3"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-96 translate-y-3"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-<?= esc($id, 'attr') ?>-title"
        class="relative flex max-h-[92vh] w-full <?= esc($sizeClass, 'attr') ?> flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:rounded-2xl"
        @click.stop
    >
        <!-- Header -->
        <div class="flex items-start justify-between gap-4 border-b border-line px-6 py-5">
            <div>
                <h2 id="modal-<?= esc($id, 'attr') ?>-title" class="font-serif text-xl font-semibold text-charcoal">
                    <?= esc($modalTitle) ?>
                </h2>
                <?php if ($description): ?>
                    <p class="mt-1 text-sm text-charcoal/60"><?= esc($description) ?></p>
                <?php endif; ?>
            </div>
            <button
                type="button"
                @click="<?= esc($id, 'attr') ?> = false"
                aria-label="Tutup"
                class="rounded-lg p-2 text-charcoal/50 transition-colors duration-150 hover:bg-ivory hover:text-charcoal"
            >
                <?= icon('x', 'h-5 w-5') ?>
            </button>
        </div>

        <!-- Body -->
        <div class="scrollbar-thin flex-1 overflow-y-auto px-6 py-5">
            <?= $content ?>
        </div>

        <!-- Footer -->
        <?php if ($footer): ?>
            <div class="flex flex-col-reverse gap-2 border-t border-line bg-ivory/60 px-6 py-4 sm:flex-row sm:justify-end">
                <?= $footer ?>
            </div>
        <?php endif; ?>
    </div>
</div>
