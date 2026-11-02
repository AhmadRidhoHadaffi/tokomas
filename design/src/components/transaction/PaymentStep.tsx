import React from 'react';
import { BanknoteIcon, LandmarkIcon, QrCodeIcon } from 'lucide-react';
import { Field, TextInput } from '../ui/FormField';
import type { TransactionDraft } from '../../hooks/useTransactionDraft';
import type { PaymentMethod } from '../../types/transaction';
import { formatRupiah } from '../../utils/format';

const methods: {value: PaymentMethod;label: string;text: string;icon: typeof BanknoteIcon;}[] = [
{ value: 'Tunai', label: 'Tunai', text: 'Bayar di kasir', icon: BanknoteIcon },
{ value: 'Transfer', label: 'Transfer', text: 'BRI / BSI / Nagari', icon: LandmarkIcon },
{ value: 'QRIS', label: 'QRIS', text: 'Semua e-wallet', icon: QrCodeIcon }];


export function PaymentStep({ draft }: {draft: TransactionDraft;}) {
  return (
    <div>
      <h2 className="font-serif text-xl font-semibold text-charcoal">Ringkasan & pembayaran</h2>
      <p className="mt-1 text-sm text-charcoal/55">Masukkan diskon bila ada, lalu pilih metode bayar.</p>

      <div className="mt-5 grid gap-4 sm:grid-cols-2">
        <Field label="Diskon (Rp)" htmlFor="discount" error={draft.discount > draft.subtotal ? 'Diskon melebihi subtotal.' : undefined}>
          <TextInput id="discount" type="number" min="0" value={draft.discount || ''} placeholder="0" onChange={(e) => draft.setDiscount(Number(e.target.value))} />
        </Field>
      </div>

      <fieldset className="mt-6">
        <legend className="mb-2 text-sm font-medium text-charcoal/80">Metode Bayar</legend>
        <div className="grid gap-3 sm:grid-cols-3">
          {methods.map(({ value, label, text, icon: Icon }) => {
            const active = draft.method === value;
            return (
              <label
                key={value}
                className={`flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition-colors duration-150 ${
                active ? 'border-gold bg-gold/10' : 'border-line bg-white hover:border-gold/50'}`
                }>
                
                <input type="radio" name="method" value={value} checked={active} onChange={() => draft.setMethod(value)} className="sr-only" />
                <span className={`flex h-10 w-10 items-center justify-center rounded-lg ${active ? 'bg-gold-gradient text-charcoal' : 'bg-ivory text-gold-dark'}`}>
                  <Icon className="h-5 w-5" strokeWidth={1.75} />
                </span>
                <span>
                  <span className="block text-sm font-semibold text-charcoal">{label}</span>
                  <span className="block text-xs text-charcoal/50">{text}</span>
                </span>
              </label>);

          })}
        </div>
      </fieldset>

      {draft.method === 'Tunai' &&
      <div className="mt-6 grid gap-4 sm:grid-cols-2">
          <Field label="Uang Diterima (Rp)" htmlFor="cash" error={draft.cashReceived > 0 && draft.cashReceived < draft.total ? 'Uang diterima kurang dari total.' : undefined}>
            <TextInput id="cash" type="number" min="0" value={draft.cashReceived || ''} placeholder={String(draft.total)} onChange={(e) => draft.setCashReceived(Number(e.target.value))} />
          </Field>
          <div className="rounded-xl bg-ivory px-4 py-3">
            <p className="text-xs text-charcoal/55">Kembalian</p>
            <p className="font-serif text-xl font-semibold text-charcoal">{formatRupiah(draft.change)}</p>
          </div>
        </div>
      }
      {draft.method === 'QRIS' &&
      <p className="mt-6 rounded-xl bg-ivory px-4 py-3 text-sm text-charcoal/70">
          Tampilkan QRIS Toko Mas Gembira di meja kasir. Lanjutkan setelah pembayaran {formatRupiah(draft.total)} berhasil.
        </p>
      }
      {draft.method === 'Transfer' &&
      <p className="mt-6 rounded-xl bg-ivory px-4 py-3 text-sm text-charcoal/70">
          Rekening BSI 7123 4567 89 a.n. Toko Mas Gembira. Pastikan dana {formatRupiah(draft.total)} sudah masuk.
        </p>
      }
    </div>);

}