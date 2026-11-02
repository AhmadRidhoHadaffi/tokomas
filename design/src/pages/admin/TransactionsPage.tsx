import React, { useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { EyeIcon, PlusIcon } from 'lucide-react';
import { SortableTable, type Column } from '../../components/ui/SortableTable';
import { SearchInput } from '../../components/ui/SearchInput';
import { TextInput } from '../../components/ui/FormField';
import { buttonClass } from '../../components/ui/Button';
import { PaymentBadge } from '../../components/admin/PaymentBadge';
import { TransactionDetailModal } from '../../components/admin/TransactionDetailModal';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { transactions } from '../../data/transactions';
import type { Transaction } from '../../types/transaction';
import { formatDateTime, formatRupiah, transactionTotal } from '../../utils/format';

export function TransactionsPage() {
  const loading = useSimulatedLoading();
  const [query, setQuery] = useState('');
  const [from, setFrom] = useState('2026-09-01');
  const [to, setTo] = useState('2026-09-25');
  const [selected, setSelected] = useState<Transaction | null>(null);

  const filtered = useMemo(
    () =>
    transactions.filter((t) => {
      const day = t.date.slice(0, 10);
      const q = query.toLowerCase();
      const matchQuery = !q || [t.id, t.customer, t.cashier].some((v) => v.toLowerCase().includes(q));
      return matchQuery && (!from || day >= from) && (!to || day <= to);
    }),
    [query, from, to]
  );

  const revenue = filtered.reduce((sum, t) => sum + transactionTotal(t.items, t.discount), 0);

  const columns: Column<Transaction>[] = [
  { key: 'id', header: 'No Transaksi', sortValue: (t) => t.id, render: (t) => <span className="font-mono text-xs text-charcoal/70">{t.id}</span> },
  { key: 'date', header: 'Tanggal', sortValue: (t) => t.date, render: (t) => formatDateTime(t.date) },
  { key: 'cashier', header: 'Kasir', sortValue: (t) => t.cashier, render: (t) => t.cashier },
  { key: 'customer', header: 'Pelanggan', sortValue: (t) => t.customer, render: (t) => <span className="font-medium text-charcoal">{t.customer}</span> },
  {
    key: 'product',
    header: 'Produk',
    render: (t) =>
    <span>
          {t.items[0].name}
          {t.items.length > 1 && <span className="text-charcoal/45"> +{t.items.length - 1} lainnya</span>}
        </span>

  },
  { key: 'total', header: 'Total', align: 'right', sortValue: (t) => transactionTotal(t.items, t.discount), render: (t) => <span className="font-medium">{formatRupiah(transactionTotal(t.items, t.discount))}</span> },
  { key: 'method', header: 'Metode Bayar', sortValue: (t) => t.method, render: (t) => <PaymentBadge method={t.method} /> },
  {
    key: 'action',
    header: 'Aksi',
    align: 'right',
    render: (t) =>
    <button type="button" onClick={() => setSelected(t)} className={buttonClass('light', 'sm')}>
          <EyeIcon className="h-3.5 w-3.5" />
          Detail
        </button>

  }];


  return (
    <div className="space-y-5">
      <div className="flex flex-col gap-3 xl:flex-row xl:items-center">
        <div className="flex items-center gap-2">
          <TextInput type="date" aria-label="Tanggal mulai" value={from} onChange={(e) => setFrom(e.target.value)} className="sm:w-44" />
          <span className="text-sm text-charcoal/40">–</span>
          <TextInput type="date" aria-label="Tanggal selesai" value={to} onChange={(e) => setTo(e.target.value)} className="sm:w-44" />
        </div>
        <SearchInput value={query} onChange={setQuery} placeholder="Cari no transaksi, pelanggan, kasir" className="xl:w-80" />
        <Link to="/owner/transaksi/baru" className={buttonClass('primary', 'md', 'xl:ml-auto')}>
          <PlusIcon className="h-4 w-4" />
          Catat Transaksi
        </Link>
      </div>

      <div className="flex flex-wrap items-baseline gap-x-8 gap-y-2 rounded-2xl border border-line bg-white px-5 py-4">
        <div>
          <p className="text-xs text-charcoal/50">Omzet periode ini</p>
          <p className="font-serif text-2xl font-semibold text-charcoal">{formatRupiah(revenue)}</p>
        </div>
        <p className="text-sm text-charcoal/55">{filtered.length} transaksi</p>
      </div>

      <SortableTable
        columns={columns}
        rows={filtered}
        rowKey={(t) => t.id}
        loading={loading}
        emptyTitle="Belum ada transaksi"
        emptyDescription="Tidak ada transaksi pada rentang tanggal ini." />
      

      <TransactionDetailModal transaction={selected} onClose={() => setSelected(null)} />
    </div>);

}