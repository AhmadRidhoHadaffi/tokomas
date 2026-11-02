import React, { useMemo, useState } from 'react';
import { PencilIcon, PlusIcon, Trash2Icon } from 'lucide-react';
import { toast } from 'sonner';
import { SortableTable, type Column } from '../../components/ui/SortableTable';
import { SearchInput } from '../../components/ui/SearchInput';
import { SelectInput } from '../../components/ui/FormField';
import { Button } from '../../components/ui/Button';
import { Badge } from '../../components/ui/Badge';
import { Modal } from '../../components/ui/Modal';
import { StockBadge } from '../../components/admin/StockBadge';
import { ProductFormModal } from '../../components/admin/ProductFormModal';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { products as initialProducts } from '../../data/products';
import type { Product } from '../../types/product';
import { formatRupiah } from '../../utils/format';

interface ProductsPageProps {
  role: 'owner' | 'karyawan';
}

export function ProductsPage({ role }: ProductsPageProps) {
  const loading = useSimulatedLoading();
  const [list, setList] = useState<Product[]>(initialProducts);
  const [query, setQuery] = useState('');
  const [metal, setMetal] = useState('Semua');
  const [karat, setKarat] = useState('Semua');
  const [formOpen, setFormOpen] = useState(false);
  const [editing, setEditing] = useState<Product | null>(null);
  const [deleting, setDeleting] = useState<Product | null>(null);

  const filtered = useMemo(
    () =>
    list.filter((p) => {
      const q = query.toLowerCase();
      const matchQuery = !q || p.name.toLowerCase().includes(q) || p.code.toLowerCase().includes(q);
      return matchQuery && (metal === 'Semua' || p.metal === metal) && (karat === 'Semua' || p.karat === karat);
    }),
    [list, query, metal, karat]
  );

  const lowCount = list.filter((p) => p.stock > 0 && p.stock <= 3).length;
  const outCount = list.filter((p) => p.stock === 0).length;

  const save = (product: Product) => {
    setList((current) => {
      const exists = current.some((p) => p.code === product.code);
      return exists ? current.map((p) => p.code === product.code ? product : p) : [product, ...current];
    });
    toast.success(editing ? 'Produk diperbarui' : 'Produk ditambahkan', { description: product.name });
    setFormOpen(false);
    setEditing(null);
  };

  const columns: Column<Product>[] = [
  { key: 'code', header: 'Kode', sortValue: (p) => p.code, render: (p) => <span className="font-mono text-xs text-charcoal/60">{p.code}</span> },
  {
    key: 'name',
    header: 'Nama Produk',
    sortValue: (p) => p.name,
    render: (p) =>
    <span className="flex items-center gap-3">
          <img src={p.image} alt="" className="h-10 w-10 rounded-lg object-cover" />
          <span>
            <span className="block font-medium text-charcoal">{p.name}</span>
            <span className="block text-xs text-charcoal/45">{p.category} · {p.model}</span>
          </span>
        </span>

  },
  { key: 'metal', header: 'Jenis', sortValue: (p) => p.metal, render: (p) => <Badge tone={p.metal === 'Emas' ? 'gold' : 'grey'}>{p.metal}</Badge> },
  { key: 'weight', header: 'Berat', align: 'right', sortValue: (p) => p.weight, render: (p) => `${p.weight.toLocaleString('id-ID')} gr` },
  { key: 'karat', header: 'Kadar', sortValue: (p) => p.karat, render: (p) => p.karat },
  { key: 'size', header: 'Ukuran', render: (p) => p.size },
  { key: 'price', header: 'Harga', align: 'right', sortValue: (p) => p.price, render: (p) => <span className="font-medium">{formatRupiah(p.price)}</span> },
  { key: 'stock', header: 'Stok', sortValue: (p) => p.stock, render: (p) => <StockBadge stock={p.stock} /> },
  {
    key: 'actions',
    header: 'Aksi',
    align: 'right',
    render: (p) =>
    <span className="inline-flex gap-1">
          <button
        type="button"
        onClick={() => {
          setEditing(p);
          setFormOpen(true);
        }}
        aria-label={`Edit ${p.name}`}
        className="rounded-lg p-2 text-charcoal/55 transition-colors duration-150 hover:bg-gold/15 hover:text-gold-deep">
        
            <PencilIcon className="h-4 w-4" />
          </button>
          {role === 'owner' &&
      <button
        type="button"
        onClick={() => setDeleting(p)}
        aria-label={`Hapus ${p.name}`}
        className="rounded-lg p-2 text-charcoal/55 transition-colors duration-150 hover:bg-crimson/10 hover:text-crimson">
        
              <Trash2Icon className="h-4 w-4" />
            </button>
      }
        </span>

  }];


  return (
    <div className="space-y-5">
      <div className="flex flex-col gap-3 lg:flex-row lg:items-center">
        <SearchInput value={query} onChange={setQuery} placeholder="Cari nama atau kode produk" className="lg:w-80" />
        <div className="grid grid-cols-2 gap-3 lg:flex">
          <SelectInput aria-label="Filter jenis" value={metal} onChange={(e) => setMetal(e.target.value)} className="lg:w-40">
            <option value="Semua">Semua jenis</option>
            <option>Emas</option>
            <option>Perak</option>
          </SelectInput>
          <SelectInput aria-label="Filter kadar" value={karat} onChange={(e) => setKarat(e.target.value)} className="lg:w-40">
            <option value="Semua">Semua kadar</option>
            <option>22K</option>
            <option>18K</option>
            <option>17K</option>
            <option>Perak 925</option>
          </SelectInput>
        </div>
        <Button
          className="lg:ml-auto"
          onClick={() => {
            setEditing(null);
            setFormOpen(true);
          }}>
          
          <PlusIcon className="h-4 w-4" />
          Tambah Produk
        </Button>
      </div>

      <p className="text-sm text-charcoal/55">
        {list.length} produk · <span className="text-yellow-800">{lowCount} hampir habis</span> ·{' '}
        <span className="text-red-700">{outCount} habis</span>
      </p>

      <SortableTable
        columns={columns}
        rows={filtered}
        rowKey={(p) => p.code}
        loading={loading}
        emptyTitle="Produk tidak ditemukan"
        emptyDescription="Coba ubah kata kunci atau filter jenis/kadar." />
      

      <ProductFormModal
        open={formOpen}
        product={editing}
        onClose={() => {
          setFormOpen(false);
          setEditing(null);
        }}
        onSave={save} />
      

      <Modal
        open={deleting !== null}
        onClose={() => setDeleting(null)}
        title="Hapus produk?"
        size="sm"
        footer={
        <>
            <Button variant="light" onClick={() => setDeleting(null)}>
              Batal
            </Button>
            <Button
            variant="danger"
            onClick={() => {
              if (!deleting) return;
              setList((l) => l.filter((p) => p.code !== deleting.code));
              toast.success('Produk dihapus', { description: deleting.name });
              setDeleting(null);
            }}>
            
              Hapus
            </Button>
          </>
        }>
        
        <p className="text-sm text-charcoal/70">
          <span className="font-semibold text-charcoal">{deleting?.name}</span> ({deleting?.code}) akan dihapus dari katalog. Riwayat
          transaksi tetap tersimpan.
        </p>
      </Modal>
    </div>);

}