import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { LogOutIcon, MailIcon, PencilIcon, PhoneIcon } from 'lucide-react';
import { toast } from 'sonner';
import { Button, buttonClass } from '../../components/ui/Button';
import { Field, TextInput } from '../../components/ui/FormField';
import { PasswordInput } from '../../components/auth/PasswordInput';
import { sessionUsers } from '../../data/navigation';
import { initials } from '../../utils/format';

export function CustomerProfilePage() {
  const [profile, setProfile] = useState({ name: sessionUsers.pelanggan.name, phone: sessionUsers.pelanggan.phone, email: sessionUsers.pelanggan.email });
  const [editing, setEditing] = useState(false);
  const [draft, setDraft] = useState(profile);
  const [passwords, setPasswords] = useState({ current: '', next: '' });
  const [error, setError] = useState<string | null>(null);

  const save = () => {
    if (draft.name.trim().length < 3 || !/^\S+@\S+\.\S+$/.test(draft.email)) {
      setError('Nama dan email valid wajib diisi.');
      return;
    }
    if (passwords.next && passwords.next.length < 8) {
      setError('Kata sandi baru minimal 8 karakter.');
      return;
    }
    if (passwords.next && !passwords.current) {
      setError('Masukkan kata sandi saat ini untuk mengganti kata sandi.');
      return;
    }
    setProfile(draft);
    setPasswords({ current: '', next: '' });
    setEditing(false);
    setError(null);
    toast.success('Profil diperbarui');
  };

  return (
    <div className="mx-auto max-w-2xl space-y-6">
      <section className="rounded-2xl border border-line bg-white p-6 text-center sm:p-8">
        <span className="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gold-gradient font-serif text-2xl font-semibold text-charcoal ring-4 ring-gold/20">
          {initials(profile.name)}
        </span>
        <h1 className="mt-4 font-serif text-2xl font-semibold text-charcoal">{profile.name}</h1>
        <p className="text-sm text-charcoal/50">Pelanggan sejak Maret 2025</p>
        <div className="mt-5 flex flex-col items-center justify-center gap-2 text-sm text-charcoal/70 sm:flex-row sm:gap-6">
          <span className="flex items-center gap-2">
            <PhoneIcon className="h-4 w-4 text-gold-dark" />
            {profile.phone}
          </span>
          <span className="flex items-center gap-2">
            <MailIcon className="h-4 w-4 text-gold-dark" />
            {profile.email}
          </span>
        </div>
        {!editing &&
        <Button
          variant="outline"
          className="mt-6"
          onClick={() => {
            setDraft(profile);
            setEditing(true);
          }}>
          
            <PencilIcon className="h-4 w-4" />
            Edit Profil
          </Button>
        }
      </section>

      {editing &&
      <section className="rounded-2xl border border-line bg-white p-6">
          <h2 className="font-serif text-lg font-semibold text-charcoal">Edit Profil</h2>
          <div className="mt-5 grid gap-4 sm:grid-cols-2">
            <Field label="Nama" htmlFor="pf-name" className="sm:col-span-2">
              <TextInput id="pf-name" value={draft.name} onChange={(e) => setDraft({ ...draft, name: e.target.value })} />
            </Field>
            <Field label="Nomor HP" htmlFor="pf-phone" hint="Perubahan nomor memerlukan verifikasi ulang.">
              <TextInput id="pf-phone" inputMode="tel" value={draft.phone} onChange={(e) => setDraft({ ...draft, phone: e.target.value })} />
            </Field>
            <Field label="Email" htmlFor="pf-email">
              <TextInput id="pf-email" type="email" value={draft.email} onChange={(e) => setDraft({ ...draft, email: e.target.value })} />
            </Field>
          </div>
          <h3 className="mt-8 text-sm font-semibold text-charcoal">Ganti Password</h3>
          <div className="mt-3 grid gap-4 sm:grid-cols-2">
            <Field label="Kata sandi saat ini" htmlFor="pf-cur">
              <PasswordInput id="pf-cur" autoComplete="current-password" value={passwords.current} onChange={(e) => setPasswords({ ...passwords, current: e.target.value })} />
            </Field>
            <Field label="Kata sandi baru" htmlFor="pf-new">
              <PasswordInput id="pf-new" autoComplete="new-password" value={passwords.next} onChange={(e) => setPasswords({ ...passwords, next: e.target.value })} />
            </Field>
          </div>
          {error && <p role="alert" className="mt-4 rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson">{error}</p>}
          <div className="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <Button variant="light" onClick={() => {setEditing(false);setError(null);}}>Batal</Button>
            <Button onClick={save}>Simpan Perubahan</Button>
          </div>
        </section>
      }

      <Link to="/masuk" className={buttonClass('ghost', 'md', 'w-full !text-crimson hover:!bg-crimson/5 md:hidden')}>
        <LogOutIcon className="h-4 w-4" />
        Keluar
      </Link>
    </div>);

}