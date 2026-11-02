import React, { useState } from 'react';
import { MinusIcon, PlusIcon, Trash2Icon } from 'lucide-react';
import { SearchInput } from '../ui/SearchInput';
import { EmptyState } from '../ui/EmptyState';
import { StockBadge } from '../admin/StockBadge';
import type { TransactionDraft } from '../../hooks/useTransactionDraft';
import { products } from '../../data/products';
import { formatRupiah } from '../../utils/format';

export function ProductStep({ draft }: {draft: TransactionDraft;}) {
  const [query, setQuery] = useState('');
  const q = query.toLowerCase();
  const results = q ? products.filter((p) => p.name.toLowerCase().includes(q) || p.code.toLowerCase().includes(q)).slice(0, 6) : products.slice(0, 6);

  return (
    <div>
      <h2 className="font-serif text-xl font-semibold text-charcoal">Tambah produk</h2>
      <p className="mt-1 text-sm text-charcoal/55">Harga dan stok tampil otomatis. Stok habis tidak dapat dipilih.</p>

      <SearchInput value={query} onChange={setQuery} placeholder="Cari nama atau kode produk" className="mt-5" />

      <ul className="mt-3 divide-y divide-line overflow-hidden rounded-xl border border-line">
        {results.map((p) =>
        <li key={p.code} className="flex items-center gap-3 bg-white px-3 py-2.5">
            <img src={p.image} alt="" className="h-10 w-10 shrink-0 rounded-lg object-cover" />
            <div className="min-w-0 flex-1">
              <p className="truncate text-sm font-medium text-charcoal">{p.name}</p>
              <p className="text-xs text-charcoal/50">
                {p.code} · {p.weight} gr · {p.karat}
              </p>
            </div>
            <div className="hidden text-right sm:block">
              <p className="text-sm font-medium text-charcoal">{formatRupiah(p.price)}</p>
              <div className="mt-0.5">
                <StockBadge stock={p.stock} />
              </div>
            </div>
            <button
            type="button"
            onClick={() => draft.addItem(p)}
            disabled={p.stock === 0}
            aria-label={`Tambah ${p.name}`}
            className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gold-gradient text-charcoal transition-[filter,opacity] duration-150 hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-30">
            
              <PlusIcon className="h-4 w-4" />
            </button>
          </li>
        )}
      </ul>

      <h3 className="mt-8 text-sm font-semibold text-charcoal">Produk dalam transaksi ({draft.items.length})</h3>
      {draft.items.length === 0 ?
      <div className="mt-3 rounded-xl border border-dashed border-line">
          <EmptyState title="Belum ada produk" description="Tambahkan produk dari daftar di atas." />
        </div> :

      <div className="scrollbar-thin mt-3 overflow-x-auto rounded-xl border border-line">
          <table className="w-full min-w-[520px] text-sm">
            <thead className="bg-ivory text-xs text-charcoal/55">
              <tr>
                <th scope="col" className="px-4 py-2.5 text-left font-semibold">Produk</th>
                <th scope="col" className="px-4 py-2.5 text-center font-semibold">Qty</th>
                <th scope="col" className="px-4 py-2.5 text-right font-semibold">Jumlah</th>
                <th scope="col" className="px-4 py-2.5"><span className="sr-only">Hapus</span></th>
              </tr>
            </thead>
            <tbody className="divide-y divide-line bg-white">
              {draft.items.map(({ product, qty }) =>
            <tr key={product.code}>
                  <td className="px-4 py-3">
                    <p className="font-medium text-charcoal">{product.name}</p>
                    <p className="text-xs text-charcoal/45">{formatRupiah(product.price)} · stok {product.stock}</p>
                  </td>
                  <td className="px-4 py-3">
                    <div className="mx-auto flex w-fit items-center rounded-lg border border-line">
                      <button type="button" onClick={() => draft.setQty(product.code, qty - 1)} aria-label="Kurangi" className="p-2 text-charcoal/60 hover:text-charcoal disabled:opacity-30" disabled={qty <= 1}>
                        <MinusIcon className="h-3.5 w-3.5" />
                      </button>
                      <span className="w-8 text-center font-medium">{qty}</span>
                      <button type="button" onClick={() => draft.setQty(product.code, qty + 1)} aria-label="Tambah" className="p-2 text-charcoal/60 hover:text-charcoal disabled:opacity-30" disabled={qty >= product.stock}>
                        <PlusIcon className="h-3.5 w-3.5" />
                      </button>
                    </div>
                  </td>
                  <td className="px-4 py-3 text-right font-medium">{formatRupiah(product.price * qty)}</td>
                  <td className="px-4 py-3 text-right">
                    <button
                  type="button"
                  onClick={() => draft.removeItem(product.code)}
                  aria-label={`Hapus ${product.name}`}
                  className="rounded-lg p-2 text-charcoal/50 transition-colors duration-150 hover:bg-crimson/10 hover:text-crimson">
                  
                      <Trash2Icon className="h-4 w-4" />
                    </button>
                  </td>
                </tr>
            )}
            </tbody>
          </table>
        </div>
      }
    </div>);

}