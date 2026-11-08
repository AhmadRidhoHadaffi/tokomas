<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<?php
$customer = $customer ?? [];
$customerName = (string) ($customer['name'] ?? session('user_name') ?? 'Pelanggan');
$customerPhone = (string) ($customer['phone'] ?? '');
$customerEmail = (string) ($customer['email'] ?? session('user_email') ?? '');

$nameParts = preg_split('/\s+/', trim($customerName)) ?: [];
$initials = '';
foreach (array_slice($nameParts, 0, 2) as $part) {
    $initials .= mb_substr($part, 0, 1);
}
$initials = mb_strtoupper($initials ?: 'P');

$months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$createdTimestamp = strtotime((string) ($customer['created_at'] ?? ''));
$since = $createdTimestamp ? $months[(int) date('n', $createdTimestamp) - 1] . ' ' . date('Y', $createdTimestamp) : '-';

$initialProfile = [
    'name' => $customerName,
    'phone' => $customerPhone,
    'email' => $customerEmail,
];

$alpineTemplate = <<<'JS'
{
    editing: false,
    saved: false,
    error: null,
    profile: __PROFILE__,
    draft: __PROFILE__,
    passwords: { current: '', next: '' },
    initials(name) {
        return (name || '').trim().split(/\s+/).slice(0, 2).map(function (part) {
            return part ? part[0] : '';
        }).join('').toUpperCase();
    },
    edit() {
        this.draft = { ...this.profile };
        this.passwords = { current: '', next: '' };
        this.error = null;
        this.saved = false;
        this.editing = true;
    },
    cancel() {
        this.editing = false;
        this.error = null;
    },
    save() {
        if (!this.draft.name || this.draft.name.trim().length < 3 || !/^\S+@\S+\.\S+$/.test(this.draft.email)) {
            this.error = 'Nama dan email valid wajib diisi.';
            return;
        }
        if (this.passwords.next && this.passwords.next.length < 8) {
            this.error = 'Kata sandi baru minimal 8 karakter.';
            return;
        }
        if (this.passwords.next && !this.passwords.current) {
            this.error = 'Masukkan kata sandi saat ini untuk mengganti kata sandi.';
            return;
        }
        this.profile = { ...this.draft };
        this.passwords = { current: '', next: '' };
        this.editing = false;
        this.error = null;
        this.saved = true;
    }
}
JS;
$alpineData = str_replace('__PROFILE__', json_encode($initialProfile), $alpineTemplate);

$inputClass = 'w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-sm text-charcoal placeholder:text-charcoal/40 outline-none transition-[border-color,box-shadow] duration-150 focus:border-gold focus:ring-4 focus:ring-gold/20 disabled:bg-ivory disabled:text-charcoal/50';
?>
<div class="mx-auto max-w-2xl space-y-6" x-data="<?= esc($alpineData, 'attr') ?>">
    <section class="rounded-2xl border border-line bg-white p-6 text-center sm:p-8">
        <span
            class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gold-gradient font-serif text-2xl font-semibold text-charcoal ring-4 ring-gold/20"
            x-text="initials(profile.name)"
        ><?= esc($initials) ?></span>
        <h1 class="mt-4 break-words font-serif text-2xl font-semibold text-charcoal" x-text="profile.name"><?= esc($customerName) ?></h1>
        <p class="text-sm text-charcoal/50">Pelanggan sejak <?= esc($since) ?></p>

        <div class="mt-5 flex flex-col items-center justify-center gap-2 text-sm text-charcoal/70 sm:flex-row sm:gap-6">
            <span class="flex min-w-0 items-center gap-2">
                <?= icon('phone', 'h-4 w-4 shrink-0 text-gold-dark', 16, 2) ?>
                <span class="break-all" x-text="profile.phone || '-'"><?= esc($customerPhone !== '' ? $customerPhone : '-') ?></span>
            </span>
            <span class="flex min-w-0 items-center gap-2">
                <?= icon('mail', 'h-4 w-4 shrink-0 text-gold-dark', 16, 2) ?>
                <span class="break-all" x-text="profile.email || '-'"><?= esc($customerEmail !== '' ? $customerEmail : '-') ?></span>
            </span>
        </div>

        <button
            type="button"
            x-show="!editing"
            @click="edit()"
            class="mt-6 inline-flex h-11 items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-gold bg-transparent px-5 text-sm font-medium text-gold-deep transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out hover:bg-gold/10 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30 active:scale-[0.98]"
        >
            <?= icon('pencil', 'h-4 w-4', 16, 2) ?>
            Edit Profil
        </button>
    </section>

    <div x-show="saved" x-cloak class="rounded-xl bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800 ring-1 ring-inset ring-emerald-200" role="status">
        <p class="flex items-center gap-2 font-medium">
            <?= icon('check-circle-2', 'h-5 w-5 shrink-0 text-emerald-600', 20, 2) ?>
            Profil diperbarui
        </p>
        <p class="mt-1 pl-7 text-xs text-emerald-700/80">Perubahan hanya tersimpan selama sesi ini.</p>
    </div>

    <section x-show="editing" x-cloak class="rounded-2xl border border-line bg-white p-6">
        <h2 class="font-serif text-lg font-semibold text-charcoal">Edit Profil</h2>
        <form @submit.prevent="save()" class="mt-5">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="pf-name" class="mb-1.5 block text-sm font-medium text-charcoal/80">Nama</label>
                    <input id="pf-name" type="text" x-model="draft.name" autocomplete="name" class="<?= esc($inputClass, 'attr') ?>">
                </div>
                <div>
                    <label for="pf-phone" class="mb-1.5 block text-sm font-medium text-charcoal/80">Nomor HP</label>
                    <input id="pf-phone" type="tel" inputmode="tel" x-model="draft.phone" autocomplete="tel" class="<?= esc($inputClass, 'attr') ?>">
                    <p class="mt-1.5 text-xs text-charcoal/50">Perubahan nomor memerlukan verifikasi ulang.</p>
                </div>
                <div>
                    <label for="pf-email" class="mb-1.5 block text-sm font-medium text-charcoal/80">Email</label>
                    <input id="pf-email" type="email" x-model="draft.email" autocomplete="email" class="<?= esc($inputClass, 'attr') ?>">
                </div>
            </div>

            <h3 class="mt-8 text-sm font-semibold text-charcoal">Ganti Password</h3>
            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="pf-cur" class="mb-1.5 block text-sm font-medium text-charcoal/80">Kata sandi saat ini</label>
                    <input id="pf-cur" type="password" x-model="passwords.current" autocomplete="current-password" class="<?= esc($inputClass, 'attr') ?>">
                </div>
                <div>
                    <label for="pf-new" class="mb-1.5 block text-sm font-medium text-charcoal/80">Kata sandi baru</label>
                    <input id="pf-new" type="password" x-model="passwords.next" autocomplete="new-password" class="<?= esc($inputClass, 'attr') ?>">
                </div>
            </div>

            <p x-show="error" x-text="error" class="mt-4 rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson" role="alert"></p>

            <p class="mt-4 text-xs text-charcoal/45">Penyimpanan permanen profil belum tersedia pada versi ini.</p>

            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    @click="cancel()"
                    class="inline-flex h-11 items-center justify-center whitespace-nowrap rounded-lg border border-line bg-white px-5 text-sm font-medium text-charcoal transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out hover:border-gold/60 hover:bg-ivory focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30 active:scale-[0.98]"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="inline-flex h-11 items-center justify-center whitespace-nowrap rounded-lg bg-gold-gradient px-5 text-sm font-medium text-charcoal shadow-sm transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out hover:brightness-110 hover:shadow-gold-glow focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30 active:scale-[0.98]"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </section>

    <form method="post" action="<?= base_url('keluar') ?>" class="lg:hidden">
        <?= csrf_field() ?>
        <button
            type="submit"
            class="inline-flex h-11 w-full items-center justify-center gap-2 whitespace-nowrap rounded-lg px-5 text-sm font-medium text-crimson transition-colors duration-150 hover:bg-crimson/5 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-crimson/20"
        >
            <?= icon('log-out', 'h-4 w-4', 16, 2) ?>
            Keluar
        </button>
    </form>
</div>
<?= $this->endSection() ?>
