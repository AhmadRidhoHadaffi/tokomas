import React, { useState } from 'react';
import { toast } from 'sonner';
import { Button } from '../../components/ui/Button';
import { Field, TextArea, TextInput } from '../../components/ui/FormField';
import { store } from '../../data/images';

const notificationSettings = [
{ key: 'status', label: 'Notifikasi status pesanan otomatis', text: 'Kirim ke pelanggan setiap kali status pesanan custom berubah.' },
{ key: 'payment', label: 'Pengingat pelunasan', text: 'Ingatkan pelanggan 1 hari setelah pesanan berstatus Selesai.' },
{ key: 'stock', label: 'Peringatan stok menipis', text: 'Beri tahu owner saat stok produk tersisa 3 atau kurang.' }];


export function SettingsPage() {
  const [toggles, setToggles] = useState<Record<string, boolean>>({ status: true, payment: true, stock: false });

  return (
    <div className="grid gap-6 xl:grid-cols-3">
      <section className="rounded-2xl border border-line bg-white p-6 xl:col-span-2">
        <h2 className="font-serif text-lg font-semibold text-charcoal">Profil Toko</h2>
        <p className="text-sm text-charcoal/55">Tampil di halaman publik dan nota transaksi.</p>
        <div className="mt-5 grid gap-4 sm:grid-cols-2">
          <Field label="Nama Toko" htmlFor="s-name">
            <TextInput id="s-name" defaultValue={store.name} />
          </Field>
          <Field label="Email" htmlFor="s-email">
            <TextInput id="s-email" type="email" defaultValue={store.email} />
          </Field>
          <Field label="Nomor Telepon" htmlFor="s-phone">
            <TextInput id="s-phone" defaultValue={store.phone} />
          </Field>
          <Field label="Nomor WhatsApp" htmlFor="s-wa">
            <TextInput id="s-wa" defaultValue="6281266778899" />
          </Field>
          <Field label="Alamat" htmlFor="s-address" className="sm:col-span-2">
            <TextArea id="s-address" defaultValue={store.address} />
          </Field>
        </div>
        <div className="mt-6 flex justify-end">
          <Button onClick={() => toast.success('Profil toko disimpan')}>Simpan Perubahan</Button>
        </div>
      </section>

      <div className="space-y-6">
        <section className="rounded-2xl bg-charcoal p-6 text-white">
          <h2 className="font-serif text-lg font-semibold">Harga Emas Hari Ini</h2>
          <p className="text-sm text-white/55">Per gram, tampil di beranda publik.</p>
          <div className="mt-5 space-y-3">
            {[
            ['22K', '1650000'],
            ['18K', '1350000'],
            ['17K', '1210000']].
            map(([k, v]) =>
            <label key={k} className="flex items-center gap-3">
                <span className="w-10 text-sm font-semibold text-gold">{k}</span>
                <input
                type="number"
                defaultValue={v}
                aria-label={`Harga ${k} per gram`}
                className="w-full rounded-lg border border-white/15 bg-white/5 px-3 py-2 text-sm text-white outline-none transition-[border-color,box-shadow] duration-150 focus:border-gold focus:ring-4 focus:ring-gold/20" />
              
              </label>
            )}
          </div>
          <Button className="mt-5 w-full" onClick={() => toast.success('Harga emas diperbarui')}>
            Perbarui Harga
          </Button>
        </section>

        <section className="rounded-2xl border border-line bg-white p-6">
          <h2 className="font-serif text-lg font-semibold text-charcoal">Notifikasi</h2>
          <ul className="mt-4 space-y-4">
            {notificationSettings.map((s) =>
            <li key={s.key} className="flex items-start justify-between gap-4">
                <div>
                  <p className="text-sm font-medium text-charcoal">{s.label}</p>
                  <p className="text-xs text-charcoal/50">{s.text}</p>
                </div>
                <button
                type="button"
                role="switch"
                aria-checked={toggles[s.key]}
                aria-label={s.label}
                onClick={() => setToggles((t) => ({ ...t, [s.key]: !t[s.key] }))}
                className={`relative h-6 w-11 shrink-0 rounded-full transition-colors duration-150 ${toggles[s.key] ? 'bg-gold' : 'bg-gray-200'}`}>
                
                  <span className={`absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-150 ease-out ${toggles[s.key] ? 'translate-x-[22px]' : 'translate-x-0.5'}`} />
                </button>
              </li>
            )}
          </ul>
        </section>
      </div>
    </div>);

}