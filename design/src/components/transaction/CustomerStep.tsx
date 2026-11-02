import React, { useState } from 'react';
import { CheckCircle2Icon, UserPlusIcon } from 'lucide-react';
import { SearchInput } from '../ui/SearchInput';
import { Field, TextInput } from '../ui/FormField';
import { EmptyState } from '../ui/EmptyState';
import type { TransactionDraft } from '../../hooks/useTransactionDraft';
import { customers } from '../../data/people';
import { initials } from '../../utils/format';

export function CustomerStep({ draft }: {draft: TransactionDraft;}) {
  const [query, setQuery] = useState('');
  const q = query.toLowerCase();
  const results = customers.filter((c) => !q || c.name.toLowerCase().includes(q) || c.phone.replace(/-/g, '').includes(q.replace(/-/g, '')));
  const isNew = draft.customer?.kind === 'new';

  return (
    <div>
      <h2 className="font-serif text-xl font-semibold text-charcoal">Pilih pelanggan</h2>
      <p className="mt-1 text-sm text-charcoal/55">Cari berdasarkan nama atau nomor HP, atau daftarkan pelanggan baru.</p>

      <div className="mt-5 flex flex-col gap-3 sm:flex-row">
        <SearchInput value={query} onChange={setQuery} placeholder="Cari nama / nomor HP" className="flex-1" />
        <button
          type="button"
          onClick={() => draft.setCustomer({ kind: 'new', name: '', phone: '' })}
          className={`inline-flex h-11 items-center justify-center gap-2 rounded-lg border px-4 text-sm font-medium transition-colors duration-150 ${
          isNew ? 'border-gold bg-gold/10 text-gold-deep' : 'border-line bg-white text-charcoal hover:border-gold/60'}`
          }>
          
          <UserPlusIcon className="h-4 w-4" />
          Pelanggan Baru
        </button>
      </div>

      {isNew && draft.customer?.kind === 'new' &&
      <div className="mt-5 grid gap-4 rounded-xl border border-gold/40 bg-ivory/60 p-4 sm:grid-cols-2">
          <Field label="Nama Lengkap" htmlFor="new-name">
            <TextInput
            id="new-name"
            value={draft.customer.name}
            onChange={(e) => draft.setCustomer({ kind: 'new', name: e.target.value, phone: draft.customer?.kind === 'new' ? draft.customer.phone : '' })}
            placeholder="Nama pelanggan" />
          
          </Field>
          <Field label="Nomor HP" htmlFor="new-phone">
            <TextInput
            id="new-phone"
            inputMode="tel"
            value={draft.customer.phone}
            onChange={(e) => draft.setCustomer({ kind: 'new', name: draft.customer?.kind === 'new' ? draft.customer.name : '', phone: e.target.value })}
            placeholder="0812xxxxxxxx" />
          
          </Field>
        </div>
      }

      <ul className="mt-5 grid gap-2 sm:grid-cols-2">
        {results.map((c) => {
          const selected = draft.customer?.kind === 'existing' && draft.customer.customer.id === c.id;
          return (
            <li key={c.id}>
              <button
                type="button"
                onClick={() => draft.setCustomer({ kind: 'existing', customer: c })}
                aria-pressed={selected}
                className={`flex w-full items-center gap-3 rounded-xl border p-3 text-left transition-colors duration-150 ${
                selected ? 'border-gold bg-gold/10' : 'border-line bg-white hover:border-gold/50'}`
                }>
                
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-ivory text-xs font-semibold text-gold-deep">
                  {initials(c.name)}
                </span>
                <span className="min-w-0 flex-1">
                  <span className="block truncate text-sm font-semibold text-charcoal">{c.name}</span>
                  <span className="block text-xs text-charcoal/50">{c.phone}</span>
                </span>
                {selected && <CheckCircle2Icon className="h-5 w-5 text-gold-dark" />}
              </button>
            </li>);

        })}
      </ul>
      {results.length === 0 && <EmptyState title="Pelanggan tidak ditemukan" description="Gunakan tombol Pelanggan Baru untuk mendaftarkan." />}
    </div>);

}