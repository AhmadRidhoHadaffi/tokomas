import React, { useState } from 'react';
import { PencilIcon, PlusIcon } from 'lucide-react';
import { toast } from 'sonner';
import { SortableTable, type Column } from '../../components/ui/SortableTable';
import { Button } from '../../components/ui/Button';
import { Badge } from '../../components/ui/Badge';
import { Modal } from '../../components/ui/Modal';
import { Field, SelectInput, TextInput } from '../../components/ui/FormField';
import { PasswordInput } from '../../components/auth/PasswordInput';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { employees as initialEmployees } from '../../data/people';
import type { Employee } from '../../types/people';
import { formatDate, initials } from '../../utils/format';

const emptyForm = { name: '', email: '', password: '', active: 'true' };

export function EmployeesPage() {
  const loading = useSimulatedLoading();
  const [list, setList] = useState<Employee[]>(initialEmployees);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Employee | null>(null);
  const [form, setForm] = useState(emptyForm);
  const [error, setError] = useState<string | null>(null);

  const openForm = (employee: Employee | null) => {
    setEditing(employee);
    setForm(employee ? { name: employee.name, email: employee.email, password: '', active: String(employee.active) } : emptyForm);
    setError(null);
    setOpen(true);
  };

  const toggle = (employee: Employee) => {
    setList((l) => l.map((e) => e.id === employee.id ? { ...e, active: !e.active } : e));
    toast.success(employee.active ? 'Akun dinonaktifkan' : 'Akun diaktifkan', { description: employee.name });
  };

  const submit = () => {
    if (form.name.trim().length < 3 || !/^\S+@\S+\.\S+$/.test(form.email)) {
      setError('Nama dan email valid wajib diisi.');
      return;
    }
    if (!editing && form.password.length < 8) {
      setError('Kata sandi minimal 8 karakter.');
      return;
    }
    if (editing) {
      setList((l) => l.map((e) => e.id === editing.id ? { ...e, name: form.name, email: form.email, active: form.active === 'true' } : e));
      toast.success('Akun karyawan diperbarui');
    } else {
      setList((l) => [...l, { id: `KRY-00${l.length + 1}`, name: form.name, email: form.email, active: form.active === 'true', createdAt: '2026-09-25' }]);
      toast.success('Akun karyawan dibuat', { description: form.email });
    }
    setOpen(false);
  };

  const columns: Column<Employee>[] = [
  {
    key: 'name',
    header: 'Nama',
    sortValue: (e) => e.name,
    render: (e) =>
    <span className="flex items-center gap-3">
          <span className="flex h-9 w-9 items-center justify-center rounded-full bg-ivory-dark text-xs font-semibold text-gold-deep">{initials(e.name)}</span>
          <span className="font-medium text-charcoal">{e.name}</span>
        </span>

  },
  { key: 'email', header: 'Email', sortValue: (e) => e.email, render: (e) => e.email },
  { key: 'status', header: 'Status Akun', sortValue: (e) => Number(e.active), render: (e) => e.active ? <Badge tone="green" dot>Aktif</Badge> : <Badge tone="grey" dot>Nonaktif</Badge> },
  { key: 'created', header: 'Tanggal Dibuat', sortValue: (e) => e.createdAt, render: (e) => formatDate(e.createdAt) },
  {
    key: 'actions',
    header: 'Aksi',
    align: 'right',
    render: (e) =>
    <span className="inline-flex items-center gap-2">
          <button type="button" onClick={() => openForm(e)} aria-label={`Edit ${e.name}`} className="rounded-lg p-2 text-charcoal/55 transition-colors duration-150 hover:bg-gold/15 hover:text-gold-deep">
            <PencilIcon className="h-4 w-4" />
          </button>
          <Button size="sm" variant={e.active ? 'light' : 'outline'} onClick={() => toggle(e)} className="w-28">
            {e.active ? 'Nonaktifkan' : 'Aktifkan'}
          </Button>
        </span>

  }];


  return (
    <div className="space-y-5">
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p className="text-sm text-charcoal/55">
          {list.filter((e) => e.active).length} akun aktif dari {list.length} karyawan. Karyawan dapat mencatat transaksi, pembukuan, dan pesanan custom.
        </p>
        <Button onClick={() => openForm(null)}>
          <PlusIcon className="h-4 w-4" />
          Tambah Akun Karyawan
        </Button>
      </div>

      <SortableTable columns={columns} rows={list} rowKey={(e) => e.id} loading={loading} emptyTitle="Belum ada karyawan" />

      <Modal
        open={open}
        onClose={() => setOpen(false)}
        title={editing ? 'Edit Akun Karyawan' : 'Tambah Akun Karyawan'}
        footer={
        <>
            <Button variant="light" onClick={() => setOpen(false)}>Batal</Button>
            <Button onClick={submit}>Simpan</Button>
          </>
        }>
        
        <div className="space-y-4">
          <Field label="Nama" htmlFor="e-name">
            <TextInput id="e-name" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} />
          </Field>
          <Field label="Email" htmlFor="e-email">
            <TextInput id="e-email" type="email" placeholder="nama@tokomasgembira.id" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} />
          </Field>
          <Field label={editing ? 'Kata Sandi Baru (opsional)' : 'Kata Sandi'} htmlFor="e-pass">
            <PasswordInput id="e-pass" autoComplete="new-password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} />
          </Field>
          <Field label="Status" htmlFor="e-status">
            <SelectInput id="e-status" value={form.active} onChange={(e) => setForm({ ...form, active: e.target.value })}>
              <option value="true">Aktif</option>
              <option value="false">Nonaktif</option>
            </SelectInput>
          </Field>
          {error && <p role="alert" className="rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson">{error}</p>}
        </div>
      </Modal>
    </div>);

}