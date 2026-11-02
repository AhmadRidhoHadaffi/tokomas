import React from 'react';
import { SortableTable, type Column } from '../ui/SortableTable';
import { Badge, StatusBadge } from '../ui/Badge';
import { SimpleBarChart } from './SalesCharts';
import { StockBadge } from './StockBadge';
import { PaymentBadge } from './PaymentBadge';
import { transactions } from '../../data/transactions';
import { ledgerEntries } from '../../data/ledger';
import { products } from '../../data/products';
import { customOrders } from '../../data/customOrders';
import { reportSeries } from '../../data/charts';
import type { Transaction, LedgerEntry } from '../../types/transaction';
import type { Product } from '../../types/product';
import type { CustomOrder } from '../../types/customOrder';
import { formatDate, formatRupiah, transactionTotal } from '../../utils/format';

export type ReportType = 'Penjualan' | 'Pembukuan' | 'Stok' | 'Pesanan Custom';

interface ReportResultProps {
  type: ReportType;
  from: string;
  to: string;
}

function Summary({ items }: {items: {label: string;value: string;emphasis?: boolean;}[];}) {
  return (
    <div className="grid gap-4 sm:grid-cols-3">
      {items.map((item) =>
      <div key={item.label} className={`rounded-2xl p-5 ${item.emphasis ? 'bg-charcoal' : 'border border-line bg-white'}`}>
          <p className={`text-sm ${item.emphasis ? 'text-white/60' : 'text-charcoal/55'}`}>{item.label}</p>
          <p className={`mt-1 font-serif text-2xl font-semibold ${item.emphasis ? 'text-gold' : 'text-charcoal'}`}>{item.value}</p>
        </div>
      )}
    </div>);

}

export function ReportResult({ type, from, to }: ReportResultProps) {
  const inRange = (iso: string) => iso.slice(0, 10) >= from && iso.slice(0, 10) <= to;
  let summary: {label: string;value: string;emphasis?: boolean;}[] = [];
  let table: React.ReactNode = null;

  if (type === 'Penjualan') {
    const rows = transactions.filter((t) => inRange(t.date));
    const total = rows.reduce((s, t) => s + transactionTotal(t.items, t.discount), 0);
    summary = [
    { label: 'Total Omzet', value: formatRupiah(total), emphasis: true },
    { label: 'Jumlah Transaksi', value: `${rows.length}` },
    { label: 'Rata-rata / Transaksi', value: formatRupiah(rows.length ? Math.round(total / rows.length) : 0) }];

    const columns: Column<Transaction>[] = [
    { key: 'id', header: 'No Transaksi', sortValue: (t) => t.id, render: (t) => <span className="font-mono text-xs">{t.id}</span> },
    { key: 'date', header: 'Tanggal', sortValue: (t) => t.date, render: (t) => formatDate(t.date) },
    { key: 'customer', header: 'Pelanggan', render: (t) => t.customer },
    { key: 'method', header: 'Metode', render: (t) => <PaymentBadge method={t.method} /> },
    { key: 'total', header: 'Total', align: 'right', sortValue: (t) => transactionTotal(t.items, t.discount), render: (t) => formatRupiah(transactionTotal(t.items, t.discount)) }];

    table = <SortableTable columns={columns} rows={rows} rowKey={(t) => t.id} emptyTitle="Tidak ada penjualan pada periode ini" />;
  }

  if (type === 'Pembukuan') {
    const rows = ledgerEntries.filter((e) => inRange(e.date));
    const income = rows.filter((e) => e.type === 'Pemasukan').reduce((s, e) => s + e.amount, 0);
    const expense = rows.filter((e) => e.type === 'Pengeluaran').reduce((s, e) => s + e.amount, 0);
    summary = [
    { label: 'Saldo Bersih', value: `${income - expense < 0 ? '− ' : ''}${formatRupiah(Math.abs(income - expense))}`, emphasis: true },
    { label: 'Total Pemasukan', value: formatRupiah(income) },
    { label: 'Total Pengeluaran', value: formatRupiah(expense) }];

    const columns: Column<LedgerEntry>[] = [
    { key: 'date', header: 'Tanggal', sortValue: (e) => e.date, render: (e) => formatDate(e.date) },
    { key: 'type', header: 'Jenis', render: (e) => <Badge tone={e.type === 'Pemasukan' ? 'green' : 'red'}>{e.type}</Badge> },
    { key: 'cat', header: 'Kategori', render: (e) => e.category },
    { key: 'desc', header: 'Keterangan', render: (e) => e.description },
    { key: 'amount', header: 'Jumlah', align: 'right', sortValue: (e) => e.amount, render: (e) => formatRupiah(e.amount) }];

    table = <SortableTable columns={columns} rows={rows} rowKey={(e) => e.id} emptyTitle="Tidak ada catatan pada periode ini" />;
  }

  if (type === 'Stok') {
    summary = [
    { label: 'Total Item', value: `${products.reduce((s, p) => s + p.stock, 0)}`, emphasis: true },
    { label: 'Hampir Habis', value: `${products.filter((p) => p.stock > 0 && p.stock <= 3).length} produk` },
    { label: 'Habis', value: `${products.filter((p) => p.stock === 0).length} produk` }];

    const columns: Column<Product>[] = [
    { key: 'code', header: 'Kode', sortValue: (p) => p.code, render: (p) => <span className="font-mono text-xs">{p.code}</span> },
    { key: 'name', header: 'Nama', sortValue: (p) => p.name, render: (p) => p.name },
    { key: 'karat', header: 'Kadar', render: (p) => p.karat },
    { key: 'value', header: 'Nilai Stok', align: 'right', sortValue: (p) => p.price * p.stock, render: (p) => formatRupiah(p.price * p.stock) },
    { key: 'stock', header: 'Stok', sortValue: (p) => p.stock, render: (p) => <StockBadge stock={p.stock} /> }];

    table = <SortableTable columns={columns} rows={products} rowKey={(p) => p.code} />;
  }

  if (type === 'Pesanan Custom') {
    const rows = customOrders.filter((o) => inRange(o.date));
    summary = [
    { label: 'Total Pesanan', value: `${rows.length}`, emphasis: true },
    { label: 'Masih Aktif', value: `${rows.filter((o) => o.status !== 'Selesai' && o.status !== 'Diambil').length}` },
    { label: 'Total DP Diterima', value: formatRupiah(rows.reduce((s, o) => s + o.dp, 0)) }];

    const columns: Column<CustomOrder>[] = [
    { key: 'id', header: 'No Pesanan', sortValue: (o) => o.id, render: (o) => <span className="font-mono text-xs">{o.id}</span> },
    { key: 'customer', header: 'Pelanggan', render: (o) => o.customer },
    { key: 'product', header: 'Produk', render: (o) => o.product },
    { key: 'status', header: 'Status', render: (o) => <StatusBadge status={o.status} /> },
    { key: 'price', header: 'Estimasi', align: 'right', sortValue: (o) => o.estimatedPrice, render: (o) => formatRupiah(o.estimatedPrice) }];

    table = <SortableTable columns={columns} rows={rows} rowKey={(o) => o.id} emptyTitle="Tidak ada pesanan pada periode ini" />;
  }

  return (
    <div className="space-y-6">
      <Summary items={summary} />
      <section className="rounded-2xl border border-line bg-white p-5">
        <h2 className="font-serif text-lg font-semibold text-charcoal">
          {type === 'Stok' ? 'Stok per kategori' : `Tren ${type.toLowerCase()} 4 bulan terakhir`}
        </h2>
        <div className="mt-4 h-64">
          <SimpleBarChart data={reportSeries[type]} currency={type === 'Penjualan' || type === 'Pembukuan'} />
        </div>
      </section>
      {table}
    </div>);

}