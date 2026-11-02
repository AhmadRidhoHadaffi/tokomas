import React from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { ArrowLeftIcon, ArrowRightIcon, CheckCircle2Icon, Loader2Icon, PrinterIcon } from 'lucide-react';
import { toast } from 'sonner';
import { Button } from '../../components/ui/Button';
import { TransactionStepper } from '../../components/transaction/TransactionStepper';
import { CustomerStep } from '../../components/transaction/CustomerStep';
import { ProductStep } from '../../components/transaction/ProductStep';
import { PaymentStep } from '../../components/transaction/PaymentStep';
import { PaymentBadge } from '../../components/admin/PaymentBadge';
import { useTransactionDraft } from '../../hooks/useTransactionDraft';
import { formatRupiah } from '../../utils/format';

const ease = [0.23, 1, 0.32, 1] as const;

export function RecordTransactionPage() {
  const draft = useTransactionDraft();

  if (draft.savedId) {
    return (
      <motion.div
        initial={{ opacity: 0, scale: 0.97 }}
        animate={{ opacity: 1, scale: 1 }}
        transition={{ duration: 0.25, ease }}
        className="mx-auto max-w-lg rounded-2xl border border-line bg-white p-8 text-center">
        
        <CheckCircle2Icon className="mx-auto h-14 w-14 text-emerald-600" strokeWidth={1.5} />
        <h2 className="mt-4 font-serif text-2xl font-semibold text-charcoal">Transaksi tersimpan</h2>
        <p className="mt-1 font-mono text-sm text-charcoal/55">{draft.savedId}</p>
        <p className="mt-5 text-sm text-charcoal/60">
          {draft.customerName} · {draft.items.length} produk
        </p>
        <p className="mt-1 font-serif text-3xl font-semibold text-charcoal">{formatRupiah(draft.total)}</p>
        <div className="mt-8 flex flex-col gap-2 sm:flex-row sm:justify-center">
          <Button onClick={() => toast.success('Nota dikirim ke printer')}>
            <PrinterIcon className="h-4 w-4" />
            Cetak Nota
          </Button>
          <Button variant="light" onClick={draft.reset}>
            Transaksi Baru
          </Button>
        </div>
      </motion.div>);

  }

  return (
    <div className="grid gap-6 lg:grid-cols-3">
      <div className="lg:col-span-2">
        <div className="rounded-2xl border border-line bg-white p-5 sm:p-6">
          <TransactionStepper current={draft.step} />
          <div className="mt-8 min-h-[320px]">
            <AnimatePresence mode="wait" initial={false}>
              <motion.div
                key={draft.step}
                initial={{ opacity: 0, x: 12 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: -12 }}
                transition={{ duration: 0.2, ease }}>
                
                {draft.step === 1 && <CustomerStep draft={draft} />}
                {draft.step === 2 && <ProductStep draft={draft} />}
                {draft.step === 3 && <PaymentStep draft={draft} />}
                {draft.step === 4 &&
                <div>
                    <h2 className="font-serif text-xl font-semibold text-charcoal">Konfirmasi & simpan</h2>
                    <p className="mt-1 text-sm text-charcoal/55">Periksa kembali sebelum menyimpan. Stok akan berkurang otomatis.</p>
                    <ul className="mt-5 divide-y divide-line rounded-xl border border-line">
                      {draft.items.map(({ product, qty }) =>
                    <li key={product.code} className="flex justify-between px-4 py-3 text-sm">
                          <span>
                            {product.name} <span className="text-charcoal/45">× {qty}</span>
                          </span>
                          <span className="font-medium">{formatRupiah(product.price * qty)}</span>
                        </li>
                    )}
                    </ul>
                  </div>
                }
              </motion.div>
            </AnimatePresence>
          </div>
          <div className="mt-8 flex justify-between border-t border-line pt-5">
            <Button variant="ghost" onClick={() => draft.setStep(draft.step - 1)} disabled={draft.step === 1}>
              <ArrowLeftIcon className="h-4 w-4" />
              Kembali
            </Button>
            {draft.step < 4 ?
            <Button onClick={() => draft.setStep(draft.step + 1)} disabled={!draft.canProceed}>
                Lanjut
                <ArrowRightIcon className="h-4 w-4" />
              </Button> :

            <Button onClick={draft.save} disabled={draft.saving}>
                {draft.saving && <Loader2Icon className="h-4 w-4 animate-spin" />}
                {draft.saving ? 'Menyimpan…' : 'Simpan Transaksi'}
              </Button>
            }
          </div>
        </div>
      </div>

      <aside className="lg:sticky lg:top-24 lg:self-start">
        <div className="rounded-2xl bg-charcoal p-6 text-white">
          <h2 className="font-serif text-lg font-semibold">Ringkasan</h2>
          <dl className="mt-5 space-y-3 text-sm">
            <div className="flex justify-between gap-4">
              <dt className="text-white/55">Pelanggan</dt>
              <dd className="truncate text-right font-medium">{draft.customerName || '—'}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-white/55">Produk</dt>
              <dd className="font-medium">{draft.items.reduce((s, i) => s + i.qty, 0)} item</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-white/55">Subtotal</dt>
              <dd>{formatRupiah(draft.subtotal)}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-white/55">Diskon</dt>
              <dd className="text-red-300">− {formatRupiah(draft.discount)}</dd>
            </div>
            <div className="flex items-center justify-between">
              <dt className="text-white/55">Metode</dt>
              <dd>
                <PaymentBadge method={draft.method} />
              </dd>
            </div>
          </dl>
          <div className="mt-5 border-t border-white/10 pt-5">
            <p className="text-xs text-white/55">Total bayar</p>
            <p className="font-serif text-3xl font-semibold text-gold">{formatRupiah(draft.total)}</p>
          </div>
        </div>
      </aside>
    </div>);

}