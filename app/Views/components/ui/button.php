<?php
/**
 * Button Component — single source of truth for buttons.
 *
 * Mirrors design/src/components/ui/Button.tsx and adds the states the
 * application needs (icons, icon-only, full width, loading, link buttons).
 *
 * @var string $variant primary|outline|outlineDark|outlineLight|ghost|danger|dark|light
 * @var string $size sm|md|lg
 * @var string $type button|submit|reset
 * @var string $class Additional classes
 * @var string $id Button ID
 * @var bool $disabled
 * @var bool $loading Shows a spinner, disables the control and sets aria-busy
 * @var string $href If set, renders as <a> instead of <button>
 * @var string $target Link target (with href)
 * @var string $rel Link rel (with href)
 * @var bool|string $download Download attribute (with href)
 * @var string $onclick
 * @var string $content Button content (text/HTML)
 * @var string $icon Lucide icon name rendered next to the content
 * @var string $iconPosition left|right
 * @var string $iconClass Icon CSS classes (default h-4 w-4)
 * @var bool $iconOnly Render only the icon (aria-label is required)
 * @var bool $fullWidth
 * @var string $name Form field name (button only)
 * @var string $value Form field value (button only)
 * @var string $ariaLabel
 * @var string $title
 * @var string $ariaControls
 * @var array $attrs Extra attributes, e.g. ['@click' => 'open = true', ':class' => '...']
 */

$uiButton = is_array($uiButton ?? null) ? $uiButton : [];

$variant      = $uiButton['variant'] ?? 'primary';
$size         = $uiButton['size'] ?? 'md';
$type         = $uiButton['type'] ?? 'button';
$class        = $uiButton['class'] ?? '';
$id           = $uiButton['id'] ?? '';
$disabled     = (bool) ($uiButton['disabled'] ?? false);
$loading      = (bool) ($uiButton['loading'] ?? false);
$href         = $uiButton['href'] ?? '';
$target       = $uiButton['target'] ?? '';
$rel          = $uiButton['rel'] ?? '';
$download     = $uiButton['download'] ?? false;
$onclick      = $uiButton['onclick'] ?? '';
$content      = $uiButton['content'] ?? '';
$icon         = $uiButton['icon'] ?? '';
$iconPosition = $uiButton['iconPosition'] ?? 'left';
$iconClass    = $uiButton['iconClass'] ?? 'h-4 w-4';
$iconOnly     = (bool) ($uiButton['iconOnly'] ?? false);
$align        = $uiButton['align'] ?? 'center';
$fullWidth    = (bool) ($uiButton['fullWidth'] ?? false);
$name         = $uiButton['name'] ?? '';
$value        = $uiButton['value'] ?? null;
$ariaLabel    = $uiButton['ariaLabel'] ?? '';
$title        = $uiButton['title'] ?? '';
$ariaControls = $uiButton['ariaControls'] ?? '';
$attrs        = is_array($uiButton['attrs'] ?? null) ? $uiButton['attrs'] : [];

$isDisabled = $disabled || $loading;

$extraClass = $class;
if ($fullWidth) {
    $extraClass .= ' w-full';
}
if ($href && $isDisabled) {
    $extraClass .= ' pointer-events-none opacity-50';
}

$finalSize = $iconOnly ? 'icon-' . ($size === 'sm' || $size === 'lg' ? $size : 'md') : $size;
$finalClass = button_class($variant, $finalSize, $extraClass, $align);

$iconHtml = '';
if ($icon !== '') {
    $iconHtml = icon((string) $icon, (string) $iconClass);
}
if ($iconOnly && $iconHtml === '') {
    // Allow a pre-rendered icon/HTML for icon-only buttons.
    $iconHtml = $content;
}

$spinner = $loading ? icon('loader-2', 'h-4 w-4 animate-spin') : '';

if ($iconOnly) {
    if ($loading) {
        $inner = $spinner;
    } elseif ($icon !== '') {
        $inner = $iconHtml . $content;
    } else {
        $inner = $iconHtml;
    }
} else {
    $inner = '';
    if ($loading) {
        $inner .= $spinner;
    } elseif ($iconHtml !== '' && $iconPosition === 'left') {
        $inner .= $iconHtml;
    }
    $inner .= $content;
    if ($iconHtml !== '' && $iconPosition === 'right') {
        $inner .= $iconHtml;
    }
}

$attributes = [];

if ($id !== '') {
    $attributes[] = 'id="' . esc((string) $id, 'attr') . '"';
}

if ($iconOnly && $ariaLabel === '' && ! array_key_exists('aria-label', $attrs) && ! array_key_exists(':aria-label', $attrs)) {
    $ariaLabel = $title !== '' ? $title : 'Tombol';
}

if ($ariaLabel !== '') {
    $attributes[] = 'aria-label="' . esc((string) $ariaLabel, 'attr') . '"';
}
if ($title !== '') {
    $attributes[] = 'title="' . esc((string) $title, 'attr') . '"';
}
if ($ariaControls !== '') {
    $attributes[] = 'aria-controls="' . esc((string) $ariaControls, 'attr') . '"';
}
if ($onclick !== '') {
    $attributes[] = 'onclick="' . esc((string) $onclick, 'attr') . '"';
}

if ($href !== '') {
    if ($target !== '') {
        $attributes[] = 'target="' . esc((string) $target, 'attr') . '"';
    }
    if ($rel !== '') {
        $attributes[] = 'rel="' . esc((string) $rel, 'attr') . '"';
    } elseif ($target === '_blank') {
        $attributes[] = 'rel="noopener noreferrer"';
    }
    if ($download !== false) {
        $attributes[] = $download === true ? 'download' : 'download="' . esc((string) $download, 'attr') . '"';
    }
    if ($isDisabled) {
        $attributes[] = 'aria-disabled="true"';
        $attributes[] = 'tabindex="-1"';
    }
} else {
    if ($name !== '') {
        $attributes[] = 'name="' . esc((string) $name, 'attr') . '"';
    }
    if ($value !== null) {
        $attributes[] = 'value="' . esc((string) $value, 'attr') . '"';
    }
    if ($isDisabled) {
        $attributes[] = 'disabled';
    }
}

if ($loading) {
    $attributes[] = 'aria-busy="true"';
}

foreach ($attrs as $attrName => $attrValue) {
    if (! is_string($attrName) || ! preg_match('/^[A-Za-z0-9_:.@-]+$/', $attrName)) {
        continue;
    }
    if ($attrValue === true) {
        $attributes[] = $attrName;
        continue;
    }
    if ($attrValue === false || $attrValue === null) {
        continue;
    }
    $attributes[] = $attrName . '="' . esc((string) $attrValue, 'attr') . '"';
}

$attrString = implode(' ', $attributes);
?>
<?php if ($href !== ''): ?>
    <a href="<?= esc((string) $href, 'attr') ?>" class="<?= esc($finalClass, 'attr') ?>" <?= $attrString ?>><?= $inner ?></a>
<?php else: ?>
    <button type="<?= esc((string) $type, 'attr') ?>" class="<?= esc($finalClass, 'attr') ?>" <?= $attrString ?>><?= $inner ?></button>
<?php endif; ?>
