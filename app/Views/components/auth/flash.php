<?php
/**
 * Flash messages for auth pages.
 */

$flashSuccess = session('success');
$flashInfo = session('info');
$flashError = session('error');
?>
<?php if ($flashSuccess): ?>
    <p role="status" class="mb-4 rounded-lg bg-emerald-50 px-3.5 py-2.5 text-sm text-emerald-700">
        <?= esc($flashSuccess) ?>
    </p>
<?php endif; ?>

<?php if ($flashInfo): ?>
    <p role="status" class="mb-4 rounded-lg bg-gold/10 px-3.5 py-2.5 text-sm text-gold-deep">
        <?= esc($flashInfo) ?>
    </p>
<?php endif; ?>

<?php if ($flashError): ?>
    <p role="alert" class="mb-4 rounded-lg bg-crimson/5 px-3.5 py-2.5 text-sm text-crimson">
        <?= esc($flashError) ?>
    </p>
<?php endif; ?>
