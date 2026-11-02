import React, { useEffect, useState } from 'react';
import { Modal } from '../ui/Modal';
import { Button } from '../ui/Button';
import { Field, SelectInput, TextInput } from '../ui/FormField';
import { UploadBox } from '../ui/UploadBox';
import type { MetalType, Product, ProductCategory } from '../../types/product';
import { images } from '../../data/images';

interface ProductFormModalProps {
  open: boolean;
  product: Product | null;
  onClose: () => void;
  onSave: (product: Product) => void;
}

const empty = { code: '', name: '', category: 'Cincin', metal: 'Emas', weight: '', karat: '22K', size: '', model: '', price: '', stock: '' };
type FormState = typeof empty;

const categories: ProductCategory[] = ['Cincin', 'Kalung', 'Gelang', 'Anting', 'Liontin'];
const karats = ['24K', '22K', '18K', '17K', 'Perak 925'];

export function ProductFormModal({ open, product, onClose, onSave }: ProductFormModalProps) {
  const [form, setForm] = useState<FormState>(empty);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!open) return;
    setError(null);
    setForm(
      product ?
      {
        code: product.code,
        name: product.name,
        category: product.category,
        metal: product.metal,
        weight: String(product.weight),
        karat: product.karat,
        size: product.size,
        model: product.model,
        price: String(product.price),
        stock: String(product.stock)
      } :
      empty
    );
  }, [open, product]);

  const set = (key: keyof FormState) => (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) =>
  setForm((f) => ({ ...f, [key]: e.target.value }));

  const submit = () => {
    if (!form.code.trim() || !form.name.trim() || !form.price || !form.weight) {
      setError('Kode, nama, berat, dan harga wajib diisi.');
      return;
    }
    onSave({
      code: form.code.trim(),
      name: form.name.trim(),
      category: form.category as ProductCategory,
      metal: form.metal as MetalType,
      weight: Number(form.weight),
      karat: form.karat,
      size: form.size || '-',
      model: form.model || '-',
      price: Number(form.price),
      stock: Number(form.stock || 0),
      image: product?.image ?? (form.metal === 'Perak' ? images.cincinPerak : images.cincinEmas)
    });
  };

  return (
    <Modal
      open={open}
      onClose={onClose}
      title={product ? 'Edit Produk' : 'Tambah Produk'}
      description={product ? `Perbarui data ${product.code}` : 'Lengkapi detail perhiasan baru.'}
      size="lg"
      footer={
      <>
          <Button variant="light" onClick={onClose}>
            Batal
          </Button>
          <Button onClick={submit}>{product ? 'Simpan Perubahan' : 'Simpan Produk'}</Button>
        </>
      }>
      
      <div className="grid gap-4 sm:grid-cols-2">
        <Field label="Kode Produk" htmlFor="p-code">
          <TextInput id="p-code" placeholder="CE-003" value={form.code} onChange={set('code')} disabled={product !== null} />
        </Field>
        <Field label="Nama Produk" htmlFor="p-name">
          <TextInput id="p-name" placeholder="Cincin Filigree" value={form.name} onChange={set('name')} />
        </Field>
        <Field label="Jenis" htmlFor="p-metal">
          <SelectInput id="p-metal" value={form.metal} onChange={set('metal')}>
            <option>Emas</option>
            <option>Perak</option>
          </SelectInput>
        </Field>
        <Field label="Kategori" htmlFor="p-cat">
          <SelectInput id="p-cat" value={form.category} onChange={set('category')}>
            {categories.map((c) =>
            <option key={c}>{c}</option>
            )}
          </SelectInput>
        </Field>
        <Field label="Berat (gram)" htmlFor="p-weight">
          <TextInput id="p-weight" type="number" step="0.1" min="0" placeholder="3,2" value={form.weight} onChange={set('weight')} />
        </Field>
        <Field label="Kadar Emas" htmlFor="p-karat">
          <SelectInput id="p-karat" value={form.karat} onChange={set('karat')}>
            {karats.map((k) =>
            <option key={k}>{k}</option>
            )}
          </SelectInput>
        </Field>
        <Field label="Ukuran" htmlFor="p-size">
          <TextInput id="p-size" placeholder="14 / 45 cm" value={form.size} onChange={set('size')} />
        </Field>
        <Field label="Model" htmlFor="p-model">
          <TextInput id="p-model" placeholder="Filigree, Polos, Rantai…" value={form.model} onChange={set('model')} />
        </Field>
        <Field label="Harga (Rp)" htmlFor="p-price">
          <TextInput id="p-price" type="number" min="0" placeholder="5280000" value={form.price} onChange={set('price')} />
        </Field>
        <Field label="Stok" htmlFor="p-stock">
          <TextInput id="p-stock" type="number" min="0" placeholder="0" value={form.stock} onChange={set('stock')} />
        </Field>
        <div className="sm:col-span-2">
          <UploadBox label="Foto Produk" initialUrl={product?.image} />
        </div>
      </div>
      {error &&
      <p role="alert" className="mt-4 rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson">
          {error}
        </p>
      }
    </Modal>);

}