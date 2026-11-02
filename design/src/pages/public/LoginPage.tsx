import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { Loader2Icon } from 'lucide-react';
import { toast } from 'sonner';
import { AuthShell } from '../../components/auth/AuthShell';
import { PasswordInput } from '../../components/auth/PasswordInput';
import { Field, TextInput } from '../../components/ui/FormField';
import { Button } from '../../components/ui/Button';
import type { Role } from '../../types/people';

const demoAccounts: {role: Role;label: string;id: string;}[] = [
{ role: 'owner', label: 'Owner', id: 'owner@tokomasgembira.id' },
{ role: 'karyawan', label: 'Karyawan', id: 'rina@tokomasgembira.id' },
{ role: 'pelanggan', label: 'Pelanggan', id: 'siti.rahma@gmail.com' }];


function detectRole(identifier: string): Role {
  const value = identifier.toLowerCase();
  if (value.startsWith('owner')) return 'owner';
  if (value.endsWith('@tokomasgembira.id')) return 'karyawan';
  return 'pelanggan';
}

const roleHome: Record<Role, string> = { owner: '/owner', karyawan: '/karyawan', pelanggan: '/pelanggan' };

export function LoginPage() {
  const navigate = useNavigate();
  const [identifier, setIdentifier] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  const onSubmit = (event: React.FormEvent) => {
    event.preventDefault();
    if (!identifier.trim() || !password) {
      setError('Nomor HP / email dan kata sandi wajib diisi.');
      return;
    }
    if (password.length < 6) {
      setError('Nomor HP / email atau kata sandi tidak sesuai.');
      return;
    }
    setError(null);
    setLoading(true);
    window.setTimeout(() => {
      const role = detectRole(identifier);
      toast.success('Berhasil masuk', { description: 'Selamat datang kembali di Toko Mas Gembira.' });
      navigate(roleHome[role]);
    }, 700);
  };

  return (
    <AuthShell title="Masuk ke Akun" subtitle="Akses dashboard sesuai peran akun Anda.">
      <form onSubmit={onSubmit} noValidate className="space-y-4">
        <Field label="Nomor HP / Email" htmlFor="identifier">
          <TextInput
            id="identifier"
            autoComplete="username"
            placeholder="0812xxxx atau nama@email.com"
            value={identifier}
            onChange={(e) => setIdentifier(e.target.value)} />
          
        </Field>
        <Field label="Kata Sandi" htmlFor="password">
          <PasswordInput
            id="password"
            autoComplete="current-password"
            placeholder="Masukkan kata sandi"
            value={password}
            onChange={(e) => setPassword(e.target.value)} />
          
        </Field>
        <div className="flex justify-end">
          <button type="button" className="text-xs font-medium text-gold-deep hover:underline">
            Lupa kata sandi?
          </button>
        </div>
        {error &&
        <p role="alert" className="rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson">
            {error}
          </p>
        }
        <Button type="submit" size="lg" className="w-full" disabled={loading}>
          {loading ? <Loader2Icon className="h-4 w-4 animate-spin" /> : null}
          {loading ? 'Memeriksa akun…' : 'Masuk'}
        </Button>
      </form>

      <p className="mt-6 text-center text-sm text-charcoal/60">
        Belum punya akun?{' '}
        <Link to="/daftar" className="font-semibold text-gold-deep hover:underline">
          Daftar di sini
        </Link>
      </p>

      <div className="mt-7 rounded-xl bg-ivory p-4">
        <p className="text-xs text-charcoal/60">
          Peran terdeteksi otomatis dari akun. Coba akun demo (kata sandi bebas, min. 6 karakter):
        </p>
        <div className="mt-3 flex flex-wrap gap-2">
          {demoAccounts.map((account) =>
          <button
            key={account.role}
            type="button"
            onClick={() => {
              setIdentifier(account.id);
              setPassword('gembira123');
              setError(null);
            }}
            className="rounded-full border border-gold/40 bg-white px-3 py-1.5 text-xs font-medium text-charcoal transition-colors duration-150 hover:border-gold hover:bg-gold/10">
            
              {account.label}
            </button>
          )}
        </div>
      </div>
    </AuthShell>);

}