import React, { useMemo, useState } from 'react';
import { PlusIcon } from 'lucide-react';
import { toast } from 'sonner';
import { SortableTable, type Column } from '../../components/ui/SortableTable';
import { Tabs } from '../../components/ui/Tabs';
import { Button } from '../../components/ui/Button';
import { Modal } from '../../components/ui/Modal';
import { Field, SelectInput, TextInput } from '../../components/ui/FormField';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { ledgerCategories, ledgerEntries } from '../../data/ledger';
import { sessionUsers } from '../../data/navigation';
import type { LedgerEntry, LedgerType } from '../../types/transaction';
import { formatDate, formatRupiah } from '../../utils/format';

interface LedgerPageProps {
  role: 'owner' | 'karyawan';
}

export function LedgerPage({ role }: LedgerPageProps) {
  const loading = useSimulatedLoading();
  const [entries, setEntries] = useState<LedgerEntry[]>(ledgerEntries);
  const [tab, setTab] = useState<LedgerType>('Pemasukan');
  const [from, setFrom] = useState('2026-09-01');
  const [to, setTo] = useState('2026-09-30');
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({ date: '2026-09-25', category: ledgerCategories.Pemasukan[0], description: '', amount: '' });
  const [error, setError] = useState<string | null>(null);

  const inRange = useMemo(() => entries.filter((e) => (!from || e.date >= from) && (!to || e.date <= to)), [entries, from, to]);
  const income = inRange.filter((e) => e.type === 'Pemasukan').reduce((s, e) => s + e.amount, 0);
  const expense = inRange.filter((e) => e.type === 'Pengeluaran').reduce((s, e) => s + e.amount, 0);
  const net = income - expense;
  const rows = inRange.filter((e) => e.type === tab);

  const openForm = () => {
    setForm({ date: '2026-09-25', category: ledgerCategories[tab][0], description: '', amount: '' });
    setError(null);
    setOpen(true);
  };

  const submit = () => {
    if (!form.description.trim() || !Number(form.amount)) {
      setError('Keterangan dan jumlah wajib diisi.');
      return;
    }
    const entry: LedgerEntry = {
      id: `BK-${Date.now()}`,
      date: form.date,
      type: tab,
      category: form.category,
      description: form.description.trim(),
      amount: Number(form.amount),
      recordedBy: sessionUsers[role].name
    };
    setEntries((list) => [entry, ...list]);
    toast.success(`Catatan ${tab.toLowerCase()} disimpan`);
    setOpen(false);
  };

  const columns: Column<LedgerEntry>[] = [
  { key: 'date', header: 'Tanggal', sortValue: (e) => e.date, render: (e) => formatDate(e.date) },
  { key: 'category', header: 'Kategori', sortValue: (e) => e.category, render: (e) => <span className="font-medium text-charcoal">{e.category}</span> },
  { key: 'description', header: 'Keterangan', render: (e) => <span className="block max-w-xs truncate">{e.description}</span> },
  {
    key: 'amount',
    header: 'Jumlah',
    align: 'right',
    sortValue: (e) => e.amount,
    render: (e) =>
    <span className={`font-semibold ${e.type === 'Pemasukan' ? 'text-emerald-700' : 'text-crimson'}`}>
          {e.type === 'Pemasukan' ? '+' : '−'} {formatRupiah(e.amount)}
        </span>

  },
  { key: 'by', header: 'Dicatat Oleh', sortValue: (e) => e.recordedBy, render: (e) => e.recordedBy }];


  return (
    <div className="space-y-6">
      <div className="grid gap-4 md:grid-cols-3">
        <div className="rounded-2xl border border-line bg-white p-5">
          <p className="text-sm text-charcoal/55">Total Pemasukan</p>
          <p className="mt-1 font-serif text-2xl font-semibold text-gold-deep">{formatRupiah(income)}</p>
        </div>
        <div className="rounded-2xl border border-line bg-white p-5">
          <p className="text-sm text-charcoal/55">Total Pengeluaran</p>
          <p className="mt-1 font-serif text-2xl font-semibold text-crimson">{formatRupiah(expense)}</p>
        </div>
        <div className="rounded-2xl bg-charcoal p-5">
          <p className="text-sm text-white/60">Saldo Bersih</p>
          <p className={`mt-1 font-serif text-2xl font-semibold ${net >= 0 ? 'text-emerald-400' : 'text-red-400'}`}>
            {net < 0 ? '− ' : ''}
            {formatRupiah(Math.abs(net))}
          </p>
        </div>
      </div>

      <div className="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <Tabs
          layoutId="ledger-tabs"
          value={tab}
          onChange={setTab}
          items={[
          { value: 'Pemasukan', label: 'Pemasukan', count: inRange.filter((e) => e.type === 'Pemasukan').length },
          { value: 'Pengeluaran', label: 'Pengeluaran', count: inRange.filter((e) => e.type === 'Pengeluaran').length }]
          } />
        
        <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
          <div className="flex items-center gap-2">
            <TextInput type="date" aria-label="Tanggal mulai" value={from} onChange={(e) => setFrom(e.target.value)} className="sm:w-40" />
            <span className="text-charcoal/40">–</span>
            <TextInput type="date" aria-label="Tanggal selesai" value={to} onChange={(e) => setTo(e.target.value)} className="sm:w-40" />
          </div>
          <Button onClick={openForm}>
            <PlusIcon className="h-4 w-4" />
            Tambah Catatan
          </Button>
        </div>
      </div>

      <SortableTable
        columns={columns}
        rows={rows}
        rowKey={(e) => e.id}
        loading={loading}
        emptyTitle={`Belum ada ${tab.toLowerCase()}`}
        emptyDescription="Tidak ada catatan pada periode ini." />
      

      <Modal
        open={open}
        onClose={() => setOpen(false)}
        title={`Tambah ${tab}`}
        footer={
        <>
            <Button variant="light" onClick={() => setOpen(false)}>
              Batal
            </Button>
            <Button onClick={submit}>Simpan Catatan</Button>
          </>
        }>
        
        <div className="grid gap-4 sm:grid-cols-2">
          <Field label="Tanggal" htmlFor="l-date">
            <TextInput id="l-date" type="date" value={form.date} onChange={(e) => setForm({ ...form, date: e.target.value })} />
          </Field>
          <Field label="Kategori" htmlFor="l-cat">
            <SelectInput id="l-cat" value={form.category} onChange={(e) => setForm({ ...form, category: e.target.value })}>
              {ledgerCategories[tab].map((c) =>
              <option key={c}>{c}</option>
              )}
            </SelectInput>
          </Field>
          <Field label="Keterangan" htmlFor="l-desc" className="sm:col-span-2">
            <TextInput id="l-desc" placeholder="Contoh: Pembelian emas batangan" value={form.description} onChange={(e) => setForm({ ...form, description: e.target.value })} />
          </Field>
          <Field label="Jumlah (Rp)" htmlFor="l-amount" className="sm:col-span-2">
            <TextInput id="l-amount" type="number" min="0" placeholder="0" value={form.amount} onChange={(e) => setForm({ ...form, amount: e.target.value })} />
          </Field>
        </div>
        {error && <p role="alert" className="mt-4 rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson">{error}</p>}
      </Modal>
    </div>);

}