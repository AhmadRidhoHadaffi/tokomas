<?php
/**
 * Form Field Component
 * 
 * @var string $type text|email|password|number|tel|date|select|textarea|file
 * @var string $name Field name
 * @var string $id Field ID
 * @var string $label Field label
 * @var string $value Current value (use old() for form validation)
 * @var string $placeholder Placeholder text
 * @var string $hint Helper text
 * @var string $error Error message
 * @var bool $required
 * @var bool $disabled
 * @var string $class Additional classes for wrapper
 * @var array $options Options for select (value => label)
 * @var string $rows Textarea rows
 * @var string $accept File input accept
 */

$type = $type ?? 'text';
$name = $name ?? '';
$id = $id ?? $name;
$label = $label ?? '';
$value = $value ?? '';
$placeholder = $placeholder ?? '';
$hint = $hint ?? '';
$error = $error ?? '';
$required = $required ?? false;
$disabled = $disabled ?? false;
$class = $class ?? '';
$options = $options ?? [];
$rows = $rows ?? '4';
$accept = $accept ?? '';

$inputClass = 'w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-sm text-charcoal placeholder:text-charcoal/40 outline-none transition-[border-color,box-shadow] duration-150 focus:border-gold focus:ring-4 focus:ring-gold/20 disabled:bg-ivory disabled:text-charcoal/50';

if ($error) {
    $inputClass .= ' border-crimson focus:border-crimson focus:ring-crimson/20';
}
?>
<div class="<?= esc($class, 'attr') ?>">
    <?php if ($label): ?>
        <label for="<?= esc($id, 'attr') ?>" class="mb-1.5 block text-sm font-medium text-charcoal/80">
            <?= esc($label) ?>
            <?php if ($required): ?>
                <span class="text-crimson">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>

    <?php if ($type === 'select'): ?>
        <select 
            name="<?= esc($name, 'attr') ?>" 
            id="<?= esc($id, 'attr') ?>"
            class="<?= esc($inputClass, 'attr') ?> appearance-none bg-[length:16px] bg-[right_12px_center] bg-no-repeat pr-9"
            style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239CA3AF' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E&quot;)"
            <?= $required ? 'required' : '' ?>
            <?= $disabled ? 'disabled' : '' ?>
        >
            <?php if ($placeholder): ?>
                <option value=""><?= esc($placeholder) ?></option>
            <?php endif; ?>
            <?php foreach ($options as $optValue => $optLabel): ?>
                <option value="<?= esc($optValue, 'attr') ?>" <?= $value == $optValue ? 'selected' : '' ?>>
                    <?= esc($optLabel) ?>
                </option>
            <?php endforeach; ?>
        </select>

    <?php elseif ($type === 'textarea'): ?>
        <textarea 
            name="<?= esc($name, 'attr') ?>" 
            id="<?= esc($id, 'attr') ?>"
            class="<?= esc($inputClass, 'attr') ?> min-h-[96px] resize-y"
            rows="<?= esc($rows, 'attr') ?>"
            placeholder="<?= esc($placeholder, 'attr') ?>"
            <?= $required ? 'required' : '' ?>
            <?= $disabled ? 'disabled' : '' ?>
        ><?= esc($value) ?></textarea>

    <?php elseif ($type === 'file'): ?>
        <input 
            type="file"
            name="<?= esc($name, 'attr') ?>" 
            id="<?= esc($id, 'attr') ?>"
            class="<?= esc($inputClass, 'attr') ?>"
            accept="<?= esc($accept, 'attr') ?>"
            <?= $required ? 'required' : '' ?>
            <?= $disabled ? 'disabled' : '' ?>
        />

    <?php else: ?>
        <input 
            type="<?= esc($type, 'attr') ?>"
            name="<?= esc($name, 'attr') ?>" 
            id="<?= esc($id, 'attr') ?>"
            value="<?= esc($value, 'attr') ?>"
            placeholder="<?= esc($placeholder, 'attr') ?>"
            class="<?= esc($inputClass, 'attr') ?>"
            <?= $required ? 'required' : '' ?>
            <?= $disabled ? 'disabled' : '' ?>
        />
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="mt-1.5 text-xs text-crimson"><?= esc($error) ?></p>
    <?php elseif ($hint): ?>
        <p class="mt-1.5 text-xs text-charcoal/50"><?= esc($hint) ?></p>
    <?php endif; ?>
</div>
