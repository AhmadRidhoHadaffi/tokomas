import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { Loader2Icon, ShieldCheckIcon } from 'lucide-react';
import { toast } from 'sonner';
import { AuthShell } from '../../components/auth/AuthShell';
import { PasswordInput } from '../../components/auth/PasswordInput';
import { Field, TextInput } from '../../components/ui/FormField';
import { Button } from '../../components/ui/Button';

interface FormState {
  name: string;
  phone: string;
  email: string;
  password: string;
  confirm: string;
}

type Errors = Partial<Record<keyof FormState, string>>;

function validate(form: FormState): Errors {
  const errors: Errors = {};
  if (form.name.trim().length < 3) errors.name = 'Nama lengkap minimal 3 karakter.';
  if (!/^08\d{8,12}$/.test(form.phone.replace(/[-\s]/g, ''))) errors.phone = 'Gunakan format 08xxxxxxxxxx.';
  if (!/^\S+@\S+\.\S+$/.test(form.email)) errors.email = 'Alamat email tidak valid.';
  if (form.password.length < 8) errors.password = 'Kata sandi minimal 8 karakter.';
  if (form.confirm !== form.password || !form.confirm) errors.confirm = 'Konfirmasi kata sandi tidak sama.';
  return errors;
}

export function RegisterPage() {
  const navigate = useNavigate();
  const [form, setForm] = useState<FormState>({ name: '', phone: '', email: '', password: '', confirm: '' });
  const [errors, setErrors] = useState<Errors>({});
  const [loading, setLoading] = useState(false);

  const update = (key: keyof FormState) => (e: React.ChangeEvent<HTMLInputElement>) =>
  setForm((f) => ({ ...f, [key]: e.target.value }));

  const onSubmit = (event: React.FormEvent) => {
    event.preventDefault();
    const result = validate(form);
    setErrors(result);
    if (Object.keys(result).length > 0) return;
    setLoading(true);
    window.setTimeout(() => {
      toast.success('Akun berhasil dibuat', { description: 'Kode verifikasi telah dikirim ke nomor HP Anda.' });
      navigate('/pelanggan');
    }, 800);
  };

  return (
    <AuthShell title="Buat Akun Pelanggan" subtitle="Gratis — pantau pesanan dan dapatkan promo eksklusif.">
      <form onSubmit={onSubmit} noValidate className="space-y-4">
        <Field label="Nama Lengkap" htmlFor="name" error={errors.name}>
          <TextInput id="name" autoComplete="name" placeholder="Nama sesuai KTP" value={form.name} onChange={update('name')} />
        </Field>
        <div className="grid gap-4 sm:grid-cols-2">
          <Field label="Nomor HP" htmlFor="phone" error={errors.phone}>
            <TextInput id="phone" inputMode="tel" autoComplete="tel" placeholder="0812xxxxxxxx" value={form.phone} onChange={update('phone')} />
          </Field>
          <Field label="Email" htmlFor="email" error={errors.email}>
            <TextInput id="email" type="email" autoComplete="email" placeholder="nama@email.com" value={form.email} onChange={update('email')} />
          </Field>
        </div>
        <Field label="Kata Sandi" htmlFor="password" error={errors.password} hint="Minimal 8 karakter.">
          <PasswordInput id="password" autoComplete="new-password" value={form.password} onChange={update('password')} />
        </Field>
        <Field label="Konfirmasi Kata Sandi" htmlFor="confirm" error={errors.confirm}>
          <PasswordInput id="confirm" autoComplete="new-password" value={form.confirm} onChange={update('confirm')} />
        </Field>

        <p className="flex items-start gap-2 rounded-lg bg-ivory px-3 py-2.5 text-xs text-charcoal/65">
          <ShieldCheckIcon className="mt-0.5 h-4 w-4 shrink-0 text-gold-dark" />
          Nomor HP akan diverifikasi oleh sistem melalui kode OTP.
        </p>

        <Button type="submit" size="lg" className="w-full" disabled={loading}>
          {loading ? <Loader2Icon className="h-4 w-4 animate-spin" /> : null}
          {loading ? 'Membuat akun…' : 'Daftar Sekarang'}
        </Button>
      </form>

      <p className="mt-6 text-center text-sm text-charcoal/60">
        Sudah punya akun?{' '}
        <Link to="/masuk" className="font-semibold text-gold-deep hover:underline">
          Masuk
        </Link>
      </p>
    </AuthShell>);

}