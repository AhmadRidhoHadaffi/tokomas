import React, { useState } from 'react';
import { ChevronRightIcon, EyeIcon } from 'lucide-react';
import { SortableTable, type Column } from '../../components/ui/SortableTable';
import { buttonClass } from '../../components/ui/Button';
import { Skeleton } from '../../components/ui/Skeleton';
import { EmptyState } from '../../components/ui/EmptyState';
import { PaymentBadge } from '../../components/admin/PaymentBadge';
import { TransactionDetailModal } from '../../components/admin/TransactionDetailModal';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { transactions } from '../../data/transactions';
import { sessionUsers } from '../../data/navigation';
import type { Transaction } from '../../types/transaction';
import { formatDate, formatRupiah, transactionTotal } from '../../utils/format';

export function CustomerTransactionsPage() {
  const loading = useSimulatedLoading();
  const [selected, setSelected] = useState<Transaction | null>(null);
  const rows = transactions.filter((t) => t.customer === sessionUsers.pelanggan.name);
  const total = rows.reduce((s, t) => s + transactionTotal(t.items, t.discount), 0);

  const columns: Column<Transaction>[] = [
  { key: 'date', header: 'Tanggal', sortValue: (t) => t.date, render: (t) => formatDate(t.date) },
  { key: 'product', header: 'Produk', render: (t) => <span className="font-medium text-charcoal">{t.items.map((i) => i.name).join(', ')}</span> },
  { key: 'total', header: 'Total', align: 'right', sortValue: (t) => transactionTotal(t.items, t.discount), render: (t) => <span className="font-semibold">{formatRupiah(transactionTotal(t.items, t.discount))}</span> },
  { key: 'method', header: 'Metode Bayar', render: (t) => <PaymentBadge method={t.method} /> },
  {
    key: 'detail',
    header: 'Detail',
    align: 'right',
    render: (t) =>
    <button type="button" onClick={() => setSelected(t)} className={buttonClass('light', 'sm')}>
          <EyeIcon className="h-3.5 w-3.5" />
          Detail
        </button>

  }];


  return (
    <div className="space-y-6">
      <div className="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
        <div>
          <h1 className="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Riwayat Transaksi</h1>
          <p className="mt-1 text-sm text-charcoal/55">Semua nota pembelian Anda tersimpan di sini.</p>
        </div>
        <div className="text-left sm:text-right">
          <p className="text-xs text-charcoal/50">Total belanja</p>
          <p className="font-serif text-2xl font-semibold text-charcoal">{formatRupiah(total)}</p>
        </div>
      </div>

      <div className="hidden md:block">
        <SortableTable columns={columns} rows={rows} rowKey={(t) => t.id} loading={loading} emptyTitle="Belum ada transaksi" emptyDescription="Transaksi di toko akan tercatat otomatis di akun Anda." />
      </div>

      <div className="md:hidden">
        {loading ?
        <div className="space-y-3">
            {[0, 1, 2].map((i) =>
          <Skeleton key={i} className="h-20 rounded-xl" />
          )}
          </div> :
        rows.length === 0 ?
        <EmptyState title="Belum ada transaksi" /> :

        <ul className="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white">
            {rows.map((t) =>
          <li key={t.id}>
                <button type="button" onClick={() => setSelected(t)} className="flex w-full items-center gap-3 px-4 py-3.5 text-left">
                  <div className="min-w-0 flex-1">
                    <p className="truncate text-sm font-medium text-charcoal">{t.items.map((i) => i.name).join(', ')}</p>
                    <p className="mt-0.5 text-xs text-charcoal/45">{formatDate(t.date)} · {t.method}</p>
                  </div>
                  <span className="text-sm font-semibold">{formatRupiah(transactionTotal(t.items, t.discount))}</span>
                  <ChevronRightIcon className="h-4 w-4 text-charcoal/30" />
                </button>
              </li>
          )}
          </ul>
        }
      </div>

      <TransactionDetailModal transaction={selected} onClose={() => setSelected(null)} />
    </div>);

}