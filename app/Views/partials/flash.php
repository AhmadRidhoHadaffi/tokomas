<?php
/**
 * Flash Message Display
 * Shows success, error, warning, info messages from session
 */

$success = session()->getFlashdata('success');
$error = session()->getFlashdata('error');
$warning = session()->getFlashdata('warning');
$info = session()->getFlashdata('info');
$errors = session()->getFlashdata('errors'); // Validation errors array
?>

<?php if ($success): ?>
    <div 
        x-data="{ show: true }" 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-init="setTimeout(() => show = false, 5000)"
        class="mb-6 flex items-start gap-3 rounded-xl bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800 ring-1 ring-inset ring-emerald-200"
        role="alert"
    >
        <?= icon('check-circle-2', 'h-5 w-5 shrink-0 text-emerald-600') ?>
        <p class="flex-1"><?= esc($success) ?></p>
        <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-800">
            <?= icon('x', 'h-4 w-4') ?>
        </button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div 
        x-data="{ show: true }" 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="mb-6 flex items-start gap-3 rounded-xl bg-crimson/5 px-4 py-3.5 text-sm text-crimson ring-1 ring-inset ring-crimson/20"
        role="alert"
    >
        <?= icon('x', 'h-5 w-5 shrink-0') ?>
        <p class="flex-1"><?= esc($error) ?></p>
        <button type="button" @click="show = false" class="hover:text-crimson-dark">
            <?= icon('x', 'h-4 w-4') ?>
        </button>
    </div>
<?php endif; ?>

<?php if ($warning): ?>
    <div 
        x-data="{ show: true }" 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="mb-6 flex items-start gap-3 rounded-xl bg-yellow-50 px-4 py-3.5 text-sm text-yellow-800 ring-1 ring-inset ring-yellow-200"
        role="alert"
    >
        <?= icon('shield-check', 'h-5 w-5 shrink-0 text-yellow-600') ?>
        <p class="flex-1"><?= esc($warning) ?></p>
        <button type="button" @click="show = false" class="text-yellow-600 hover:text-yellow-800">
            <?= icon('x', 'h-4 w-4') ?>
        </button>
    </div>
<?php endif; ?>

<?php if ($info): ?>
    <div 
        x-data="{ show: true }" 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="mb-6 flex items-start gap-3 rounded-xl bg-blue-50 px-4 py-3.5 text-sm text-blue-800 ring-1 ring-inset ring-blue-200"
        role="alert"
    >
        <?= icon('bell', 'h-5 w-5 shrink-0 text-blue-600') ?>
        <p class="flex-1"><?= esc($info) ?></p>
        <button type="button" @click="show = false" class="text-blue-600 hover:text-blue-800">
            <?= icon('x', 'h-4 w-4') ?>
        </button>
    </div>
<?php endif; ?>
