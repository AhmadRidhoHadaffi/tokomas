import React, { useMemo, useState } from 'react';
import { EyeIcon, PencilIcon, PlusIcon } from 'lucide-react';
import { toast } from 'sonner';
import { SortableTable, type Column } from '../../components/ui/SortableTable';
import { SearchInput } from '../../components/ui/SearchInput';
import { Button } from '../../components/ui/Button';
import { Modal } from '../../components/ui/Modal';
import { Tabs } from '../../components/ui/Tabs';
import { StatusBadge } from '../../components/ui/Badge';
import { EmptyState } from '../../components/ui/EmptyState';
import { Field, TextArea, TextInput } from '../../components/ui/FormField';
import { PaymentBadge } from '../../components/admin/PaymentBadge';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { customers as initialCustomers } from '../../data/people';
import { transactions } from '../../data/transactions';
import { customOrders } from '../../data/customOrders';
import type { Customer } from '../../types/people';
import { formatDate, formatRupiah, initials, transactionTotal } from '../../utils/format';

const emptyForm = { name: '', phone: '', email: '', address: '' };

export function CustomersPage() {
  const loading = useSimulatedLoading();
  const [list, setList] = useState<Customer[]>(initialCustomers);
  const [query, setQuery] = useState('');
  const [detail, setDetail] = useState<Customer | null>(null);
  const [detailTab, setDetailTab] = useState<'trx' | 'custom'>('trx');
  const [formOpen, setFormOpen] = useState(false);
  const [editing, setEditing] = useState<Customer | null>(null);
  const [form, setForm] = useState(emptyForm);
  const [error, setError] = useState<string | null>(null);

  const filtered = useMemo(() => {
    const q = query.toLowerCase();
    return list.filter((c) => !q || c.name.toLowerCase().includes(q) || c.phone.includes(q));
  }, [list, query]);

  const openForm = (customer: Customer | null) => {
    setEditing(customer);
    setForm(customer ? { name: customer.name, phone: customer.phone, email: customer.email, address: customer.address } : emptyForm);
    setError(null);
    setFormOpen(true);
  };

  const submit = () => {
    if (form.name.trim().length < 3 || form.phone.trim().length < 10) {
      setError('Nama dan nomor HP wajib diisi dengan benar.');
      return;
    }
    if (editing) {
      setList((l) => l.map((c) => c.id === editing.id ? { ...c, ...form } : c));
      toast.success('Data pelanggan diperbarui');
    } else {
      setList((l) => [
      { id: `PLG-0${l.length + 1}`, ...form, totalTransactions: 0, totalSpent: 0, joined: '2026-09-25' },
      ...l]
      );
      toast.success('Pelanggan ditambahkan', { description: form.name });
    }
    setFormOpen(false);
  };

  const columns: Column<Customer>[] = [
  { key: 'no', header: 'No', render: (_, i) => <span className="text-charcoal/45">{i + 1}</span> },
  {
    key: 'name',
    header: 'Nama',
    sortValue: (c) => c.name,
    render: (c) =>
    <span className="flex items-center gap-3">
          <span className="flex h-9 w-9 items-center justify-center rounded-full bg-ivory-dark text-xs font-semibold text-gold-deep">{initials(c.name)}</span>
          <span className="font-medium text-charcoal">{c.name}</span>
        </span>

  },
  { key: 'phone', header: 'Nomor HP', render: (c) => c.phone },
  { key: 'address', header: 'Alamat', render: (c) => <span className="block max-w-[220px] truncate">{c.address}</span> },
  {
    key: 'trx',
    header: 'Total Transaksi',
    align: 'right',
    sortValue: (c) => c.totalSpent,
    render: (c) =>
    <span>
          <span className="block font-medium">{formatRupiah(c.totalSpent)}</span>
          <span className="block text-xs text-charcoal/45">{c.totalTransactions} transaksi</span>
        </span>

  },
  { key: 'joined', header: 'Bergabung', sortValue: (c) => c.joined, render: (c) => formatDate(c.joined) },
  {
    key: 'actions',
    header: 'Aksi',
    align: 'right',
    render: (c) =>
    <span className="inline-flex gap-1">
          <button type="button" onClick={() => {setDetail(c);setDetailTab('trx');}} aria-label={`Detail ${c.name}`} className="rounded-lg p-2 text-charcoal/55 transition-colors duration-150 hover:bg-gold/15 hover:text-gold-deep">
            <EyeIcon className="h-4 w-4" />
          </button>
          <button type="button" onClick={() => openForm(c)} aria-label={`Edit ${c.name}`} className="rounded-lg p-2 text-charcoal/55 transition-colors duration-150 hover:bg-gold/15 hover:text-gold-deep">
            <PencilIcon className="h-4 w-4" />
          </button>
        </span>

  }];


  const detailTrx = detail ? transactions.filter((t) => t.customer === detail.name) : [];
  const detailOrders = detail ? customOrders.filter((o) => o.customer === detail.name) : [];

  return (
    <div className="space-y-5">
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
        <SearchInput value={query} onChange={setQuery} placeholder="Cari nama atau nomor HP" className="sm:w-80" />
        <Button className="sm:ml-auto" onClick={() => openForm(null)}>
          <PlusIcon className="h-4 w-4" />
          Tambah Pelanggan
        </Button>
      </div>

      <SortableTable columns={columns} rows={filtered} rowKey={(c) => c.id} loading={loading} emptyTitle="Pelanggan tidak ditemukan" emptyDescription="Periksa kembali kata kunci pencarian." />

      <Modal open={detail !== null} onClose={() => setDetail(null)} title="Detail Pelanggan" size="lg">
        {detail &&
        <div>
            <div className="flex flex-col gap-4 sm:flex-row sm:items-center">
              <span className="flex h-14 w-14 items-center justify-center rounded-full bg-gold-gradient font-semibold text-charcoal">{initials(detail.name)}</span>
              <div className="flex-1">
                <p className="font-serif text-xl font-semibold text-charcoal">{detail.name}</p>
                <p className="text-sm text-charcoal/55">{detail.phone} · {detail.email}</p>
                <p className="text-sm text-charcoal/55">{detail.address}</p>
              </div>
              <div className="rounded-xl bg-ivory px-4 py-3 text-right">
                <p className="text-xs text-charcoal/50">Total belanja</p>
                <p className="font-serif text-lg font-semibold text-charcoal">{formatRupiah(detail.totalSpent)}</p>
              </div>
            </div>
            <div className="mt-6">
              <Tabs
              layoutId="customer-detail-tabs"
              value={detailTab}
              onChange={setDetailTab}
              items={[
              { value: 'trx', label: 'Riwayat Transaksi', count: detailTrx.length },
              { value: 'custom', label: 'Riwayat Pesanan Custom', count: detailOrders.length }]
              } />
            
              <ul className="divide-y divide-line">
                {detailTab === 'trx' &&
              detailTrx.map((t) =>
              <li key={t.id} className="flex items-center justify-between gap-3 py-3 text-sm">
                      <div>
                        <p className="font-medium text-charcoal">{t.items.map((i) => i.name).join(', ')}</p>
                        <p className="text-xs text-charcoal/45">{t.id} · {formatDate(t.date)}</p>
                      </div>
                      <div className="flex items-center gap-3">
                        <PaymentBadge method={t.method} />
                        <span className="font-medium">{formatRupiah(transactionTotal(t.items, t.discount))}</span>
                      </div>
                    </li>
              )}
                {detailTab === 'custom' &&
              detailOrders.map((o) =>
              <li key={o.id} className="flex items-center justify-between gap-3 py-3 text-sm">
                      <div>
                        <p className="font-medium text-charcoal">{o.product}</p>
                        <p className="text-xs text-charcoal/45">{o.id} · {formatDate(o.date)}</p>
                      </div>
                      <StatusBadge status={o.status} />
                    </li>
              )}
              </ul>
              {(detailTab === 'trx' && detailTrx.length === 0 || detailTab === 'custom' && detailOrders.length === 0) &&
            <EmptyState title={detailTab === 'trx' ? 'Belum ada transaksi' : 'Belum ada pesanan custom'} />
            }
            </div>
          </div>
        }
      </Modal>

      <Modal
        open={formOpen}
        onClose={() => setFormOpen(false)}
        title={editing ? 'Edit Pelanggan' : 'Tambah Pelanggan'}
        footer={
        <>
            <Button variant="light" onClick={() => setFormOpen(false)}>Batal</Button>
            <Button onClick={submit}>Simpan</Button>
          </>
        }>
        
        <div className="grid gap-4 sm:grid-cols-2">
          <Field label="Nama Lengkap" htmlFor="c-name" className="sm:col-span-2">
            <TextInput id="c-name" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} />
          </Field>
          <Field label="Nomor HP" htmlFor="c-phone">
            <TextInput id="c-phone" inputMode="tel" value={form.phone} onChange={(e) => setForm({ ...form, phone: e.target.value })} />
          </Field>
          <Field label="Email" htmlFor="c-email">
            <TextInput id="c-email" type="email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} />
          </Field>
          <Field label="Alamat" htmlFor="c-address" className="sm:col-span-2">
            <TextArea id="c-address" value={form.address} onChange={(e) => setForm({ ...form, address: e.target.value })} />
          </Field>
        </div>
        {error && <p role="alert" className="mt-4 rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson">{error}</p>}
      </Modal>
    </div>);

}