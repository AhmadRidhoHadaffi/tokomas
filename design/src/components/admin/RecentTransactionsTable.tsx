import React, { useState } from 'react';
import { SortableTable, type Column } from '../ui/SortableTable';
import { Badge } from '../ui/Badge';
import { PaymentBadge } from './PaymentBadge';
import { TransactionDetailModal } from './TransactionDetailModal';
import type { Transaction } from '../../types/transaction';
import { formatDateTime, formatRupiah, transactionTotal } from '../../utils/format';

interface RecentTransactionsTableProps {
  rows: Transaction[];
  loading?: boolean;
}

export function RecentTransactionsTable({ rows, loading = false }: RecentTransactionsTableProps) {
  const [selected, setSelected] = useState<Transaction | null>(null);

  const columns: Column<Transaction>[] = [
  { key: 'no', header: 'No', render: (_, i) => <span className="text-charcoal/45">{i + 1}</span> },
  { key: 'date', header: 'Tanggal', sortValue: (r) => r.date, render: (r) => formatDateTime(r.date) },
  { key: 'customer', header: 'Pelanggan', sortValue: (r) => r.customer, render: (r) => <span className="font-medium text-charcoal">{r.customer}</span> },
  {
    key: 'total',
    header: 'Total',
    align: 'right',
    sortValue: (r) => transactionTotal(r.items, r.discount),
    render: (r) => <span className="font-medium">{formatRupiah(transactionTotal(r.items, r.discount))}</span>
  },
  { key: 'method', header: 'Metode Bayar', render: (r) => <PaymentBadge method={r.method} /> },
  {
    key: 'status',
    header: 'Status',
    render: (r) =>
    <Badge tone={r.status === 'Lunas' ? 'green' : 'yellow'} dot>
          {r.status}
        </Badge>

  }];


  return (
    <>
      <SortableTable
        columns={columns}
        rows={rows}
        rowKey={(r) => r.id}
        loading={loading}
        onRowClick={setSelected}
        emptyTitle="Belum ada transaksi"
        emptyDescription="Transaksi yang dicatat hari ini akan muncul di sini." />
      
      <TransactionDetailModal transaction={selected} onClose={() => setSelected(null)} />
    </>);

}