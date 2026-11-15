<?php
/**
 * Development helper: show the OTP that would normally be sent via SMS/WhatsApp.
 *
 * @var string|null $debugCode
 */
?>
<?php if (! empty($debugCode)): ?>
    <div class="rounded-lg border border-gold/40 bg-gold/10 px-3.5 py-3">
        <p class="text-xs font-medium uppercase tracking-wide text-gold-deep">Mode pengembangan</p>
        <p class="mt-1 text-sm text-charcoal/70">
            Kode OTP: <span class="font-mono text-base font-semibold tracking-[0.2em] text-charcoal"><?= esc($debugCode) ?></span>
        </p>
    </div>
<?php endif; ?>
