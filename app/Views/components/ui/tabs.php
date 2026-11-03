<?php
/**
 * Tabs Component (Alpine.js)
 * 
 * Usage:
 * <div x-data="{ activeTab: 'tab1' }">
 *     <?= view('components/ui/tabs', [
 *         'items' => [
 *             ['value' => 'tab1', 'label' => 'Tab 1', 'count' => 5],
 *             ['value' => 'tab2', 'label' => 'Tab 2'],
 *         ],
 *         'model' => 'activeTab',
 *         'variant' => 'underline',
 *     ]) ?>
 *     <div x-show="activeTab === 'tab1'">Content 1</div>
 *     <div x-show="activeTab === 'tab2'">Content 2</div>
 * </div>
 * 
 * @var array $items [['value' => '', 'label' => '', 'count' => 0], ...]
 * @var string $model Alpine model name
 * @var string $variant underline|pill
 */

$items = $items ?? [];
$model = $model ?? 'activeTab';
$variant = $variant ?? 'underline';
?>

<?php if ($variant === 'pill'): ?>
    <div role="tablist" class="scrollbar-thin flex gap-1.5 overflow-x-auto pb-1">
        <?php foreach ($items as $item): ?>
            <button
                role="tab"
                type="button"
                @click="<?= esc($model, 'attr') ?> = '<?= esc($item['value'], 'attr') ?>'"
                :aria-selected="<?= esc($model, 'attr') ?> === '<?= esc($item['value'], 'attr') ?>'"
                :class="<?= esc($model, 'attr') ?> === '<?= esc($item['value'], 'attr') ?>' ? 'bg-gold-gradient text-charcoal' : 'text-charcoal/60 hover:bg-white hover:text-charcoal'"
                class="relative whitespace-nowrap rounded-full px-4 py-2 text-sm font-medium transition-colors duration-150"
            >
                <span class="relative flex items-center gap-2">
                    <?= esc($item['label']) ?>
                    <?php if (isset($item['count'])): ?>
                        <span 
                            :class="<?= esc($model, 'attr') ?> === '<?= esc($item['value'], 'attr') ?>' ? 'bg-charcoal/15' : 'bg-charcoal/5'"
                            class="rounded-full px-1.5 text-[11px]"
                        >
                            <?= esc($item['count']) ?>
                        </span>
                    <?php endif; ?>
                </span>
            </button>
        <?php endforeach; ?>
    </div>

<?php else: ?>
    <div role="tablist" class="scrollbar-thin flex gap-1 overflow-x-auto border-b border-line">
        <?php foreach ($items as $item): ?>
            <button
                role="tab"
                type="button"
                @click="<?= esc($model, 'attr') ?> = '<?= esc($item['value'], 'attr') ?>'"
                :aria-selected="<?= esc($model, 'attr') ?> === '<?= esc($item['value'], 'attr') ?>'"
                :class="<?= esc($model, 'attr') ?> === '<?= esc($item['value'], 'attr') ?>' ? 'text-charcoal' : 'text-charcoal/55 hover:text-charcoal'"
                class="relative whitespace-nowrap px-4 py-3 text-sm font-medium transition-colors duration-150"
            >
                <span class="flex items-center gap-2">
                    <?= esc($item['label']) ?>
                    <?php if (isset($item['count'])): ?>
                        <span 
                            :class="<?= esc($model, 'attr') ?> === '<?= esc($item['value'], 'attr') ?>' ? 'bg-gold/20 text-gold-deep' : 'bg-charcoal/5'"
                            class="rounded-full px-2 py-0.5 text-[11px]"
                        >
                            <?= esc($item['count']) ?>
                        </span>
                    <?php endif; ?>
                </span>
                <span 
                    x-show="<?= esc($model, 'attr') ?> === '<?= esc($item['value'], 'attr') ?>'"
                    class="absolute inset-x-2 -bottom-px h-0.5 rounded-full bg-gold transition-opacity duration-220"
                ></span>
            </button>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
