import React, { useEffect, useMemo, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import { EyeIcon, PlusIcon } from 'lucide-react';
import { toast } from 'sonner';
import { SortableTable, type Column } from '../../components/ui/SortableTable';
import { Tabs } from '../../components/ui/Tabs';
import { SearchInput } from '../../components/ui/SearchInput';
import { Button, buttonClass } from '../../components/ui/Button';
import { StatusBadge } from '../../components/ui/Badge';
import { CustomOrderDetailModal } from '../../components/admin/CustomOrderDetailModal';
import { CustomOrderFormModal } from '../../components/admin/CustomOrderFormModal';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { customOrders, customStatusFlow } from '../../data/customOrders';
import { sessionUsers } from '../../data/navigation';
import type { CustomOrder, CustomStatus } from '../../types/customOrder';
import { formatDate, formatRupiah } from '../../utils/format';

interface CustomOrdersPageProps {
  role: 'owner' | 'karyawan';
}

type Filter = 'Semua' | CustomStatus;

export function CustomOrdersPage({ role }: CustomOrdersPageProps) {
  const loading = useSimulatedLoading();
  const [params, setParams] = useSearchParams();
  const [orders, setOrders] = useState<CustomOrder[]>(customOrders);
  const [filter, setFilter] = useState<Filter>('Semua');
  const [query, setQuery] = useState('');
  const [selectedId, setSelectedId] = useState<string | null>(null);
  const [formOpen, setFormOpen] = useState(false);
  const author = sessionUsers[role].name;

  useEffect(() => {
    if (params.get('baru') === '1' && role === 'karyawan') {
      setFormOpen(true);
      params.delete('baru');
      setParams(params, { replace: true });
    }
  }, [params, setParams, role]);

  const filtered = useMemo(
    () =>
    orders.filter((o) => {
      const q = query.toLowerCase();
      const matchQuery = !q || [o.id, o.customer, o.product].some((v) => v.toLowerCase().includes(q));
      return matchQuery && (filter === 'Semua' || o.status === filter);
    }),
    [orders, filter, query]
  );

  const selected = orders.find((o) => o.id === selectedId) ?? null;

  const updateStatus = (id: string, status: CustomStatus) => {
    setOrders((list) =>
    list.map((o) => o.id === id ? { ...o, status, history: [...o.history, { status, date: new Date().toISOString(), by: author }] } : o)
    );
    toast.success(`Status diperbarui: ${status}`, { description: 'Notifikasi terkirim ke pelanggan.' });
  };

  const tabs = [
  { value: 'Semua' as Filter, label: 'Semua', count: orders.length },
  ...customStatusFlow.map((s) => ({ value: s as Filter, label: s, count: orders.filter((o) => o.status === s).length }))];


  const columns: Column<CustomOrder>[] = [
  { key: 'id', header: 'No Pesanan', sortValue: (o) => o.id, render: (o) => <span className="font-mono text-xs text-charcoal/70">{o.id}</span> },
  { key: 'customer', header: 'Pelanggan', sortValue: (o) => o.customer, render: (o) => <span className="font-medium text-charcoal">{o.customer}</span> },
  {
    key: 'product',
    header: 'Produk Custom',
    sortValue: (o) => o.product,
    render: (o) =>
    <span className="flex items-center gap-3">
          <img src={o.referenceImage} alt="" className="h-9 w-9 rounded-lg object-cover" />
          {o.product}
        </span>

  },
  { key: 'dp', header: 'DP', align: 'right', sortValue: (o) => o.dp, render: (o) => formatRupiah(o.dp) },
  {
    key: 'rest',
    header: 'Sisa Bayar',
    align: 'right',
    sortValue: (o) => o.estimatedPrice - o.dp,
    render: (o) => <span className="font-medium text-crimson">{formatRupiah(o.estimatedPrice - o.dp)}</span>
  },
  { key: 'status', header: 'Status', sortValue: (o) => customStatusFlow.indexOf(o.status), render: (o) => <StatusBadge status={o.status} /> },
  { key: 'date', header: 'Tanggal', sortValue: (o) => o.date, render: (o) => formatDate(o.date) },
  {
    key: 'action',
    header: 'Aksi',
    align: 'right',
    render: (o) =>
    <button type="button" onClick={() => setSelectedId(o.id)} className={buttonClass('light', 'sm')}>
          <EyeIcon className="h-3.5 w-3.5" />
          Detail
        </button>

  }];


  return (
    <div className="space-y-5">
      <div className="flex flex-col gap-3 lg:flex-row lg:items-center">
        <SearchInput value={query} onChange={setQuery} placeholder="Cari no pesanan, pelanggan, produk" className="lg:w-96" />
        {role === 'karyawan' &&
        <Button className="lg:ml-auto" onClick={() => setFormOpen(true)}>
            <PlusIcon className="h-4 w-4" />
            Tambah Pesanan Custom
          </Button>
        }
      </div>

      <Tabs variant="pill" layoutId="custom-status-tabs" items={tabs} value={filter} onChange={setFilter} />

      <SortableTable
        columns={columns}
        rows={filtered}
        rowKey={(o) => o.id}
        loading={loading}
        emptyTitle="Belum ada pesanan"
        emptyDescription={filter === 'Semua' ? 'Pesanan custom baru akan muncul di sini.' : `Tidak ada pesanan berstatus "${filter}".`} />
      

      <CustomOrderDetailModal order={selected} onClose={() => setSelectedId(null)} onUpdateStatus={updateStatus} />
      <CustomOrderFormModal
        open={formOpen}
        author={author}
        onClose={() => setFormOpen(false)}
        onSave={(order) => {
          setOrders((list) => [order, ...list]);
          setFormOpen(false);
          toast.success('Pesanan custom ditambahkan', { description: `${order.id} · ${order.customer}` });
        }} />
      
    </div>);

}