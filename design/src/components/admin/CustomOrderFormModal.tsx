import React, { useEffect, useState } from 'react';
import { Modal } from '../ui/Modal';
import { Button } from '../ui/Button';
import { Field, SelectInput, TextArea, TextInput } from '../ui/FormField';
import { UploadBox } from '../ui/UploadBox';
import type { CustomOrder } from '../../types/customOrder';
import { customers } from '../../data/people';
import { images } from '../../data/images';

interface CustomOrderFormModalProps {
  open: boolean;
  onClose: () => void;
  onSave: (order: CustomOrder) => void;
  author: string;
}

const empty = { customerId: '', jewelryType: 'Cincin', model: '', engraving: '', karat: '18K', weight: '', price: '', dp: '', notes: '' };

const typeImage: Record<string, string> = {
  Cincin: images.cincinPasangan,
  Kalung: images.kalungEmas,
  Gelang: images.gelangEmas,
  Anting: images.antingEmas,
  Liontin: images.kalungEmas
};

export function CustomOrderFormModal({ open, onClose, onSave, author }: CustomOrderFormModalProps) {
  const [form, setForm] = useState(empty);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (open) {
      setForm(empty);
      setError(null);
    }
  }, [open]);

  const set = (key: keyof typeof empty) => (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) =>
  setForm((f) => ({ ...f, [key]: e.target.value }));

  const submit = () => {
    const customer = customers.find((c) => c.id === form.customerId);
    if (!customer || !form.model.trim() || !Number(form.price)) {
      setError('Pelanggan, model, dan harga estimasi wajib diisi.');
      return;
    }
    if (Number(form.dp) > Number(form.price)) {
      setError('DP tidak boleh melebihi harga estimasi.');
      return;
    }
    const now = new Date().toISOString();
    onSave({
      id: `PC-2026-0${33 + Math.floor(Math.random() * 60)}`,
      customer: customer.name,
      phone: customer.phone,
      product: `${form.jewelryType} ${form.model}`.trim(),
      jewelryType: form.jewelryType,
      model: form.model,
      engraving: form.engraving || '-',
      karat: form.karat,
      weight: Number(form.weight || 0),
      estimatedPrice: Number(form.price),
      dp: Number(form.dp || 0),
      status: 'Masuk',
      date: now.slice(0, 10),
      dueDate: '2026-10-20',
      referenceImage: typeImage[form.jewelryType],
      notes: form.notes || '-',
      history: [{ status: 'Masuk', date: now, by: author }]
    });
  };

  return (
    <Modal
      open={open}
      onClose={onClose}
      title="Tambah Pesanan Custom"
      description="Pelanggan menerima notifikasi setelah pesanan disimpan."
      size="lg"
      footer={
      <>
          <Button variant="light" onClick={onClose}>
            Batal
          </Button>
          <Button onClick={submit}>Simpan Pesanan</Button>
        </>
      }>
      
      <div className="grid gap-4 sm:grid-cols-2">
        <Field label="Pilih Pelanggan" htmlFor="co-customer" className="sm:col-span-2">
          <SelectInput id="co-customer" value={form.customerId} onChange={set('customerId')}>
            <option value="">— Pilih pelanggan —</option>
            {customers.map((c) =>
            <option key={c.id} value={c.id}>
                {c.name} · {c.phone}
              </option>
            )}
          </SelectInput>
        </Field>
        <Field label="Jenis Perhiasan" htmlFor="co-type">
          <SelectInput id="co-type" value={form.jewelryType} onChange={set('jewelryType')}>
            {Object.keys(typeImage).map((t) =>
            <option key={t}>{t}</option>
            )}
          </SelectInput>
        </Field>
        <Field label="Model" htmlFor="co-model">
          <TextInput id="co-model" placeholder="Couple polos doff" value={form.model} onChange={set('model')} />
        </Field>
        <Field label="Nama Ukiran" htmlFor="co-engrave">
          <TextInput id="co-engrave" placeholder="Siti & Fajar" value={form.engraving} onChange={set('engraving')} />
        </Field>
        <Field label="Kadar Emas" htmlFor="co-karat">
          <SelectInput id="co-karat" value={form.karat} onChange={set('karat')}>
            <option>22K</option>
            <option>18K</option>
            <option>17K</option>
            <option>Perak 925</option>
          </SelectInput>
        </Field>
        <Field label="Berat Estimasi (gr)" htmlFor="co-weight">
          <TextInput id="co-weight" type="number" step="0.1" min="0" value={form.weight} onChange={set('weight')} />
        </Field>
        <Field label="Harga Estimasi (Rp)" htmlFor="co-price">
          <TextInput id="co-price" type="number" min="0" value={form.price} onChange={set('price')} />
        </Field>
        <Field label="DP (Rp)" htmlFor="co-dp">
          <TextInput id="co-dp" type="number" min="0" value={form.dp} onChange={set('dp')} />
        </Field>
        <div className="sm:col-span-2">
          <UploadBox label="Gambar Referensi" />
        </div>
        <Field label="Catatan" htmlFor="co-notes" className="sm:col-span-2">
          <TextArea id="co-notes" placeholder="Ukuran jari, font ukiran, dll." value={form.notes} onChange={set('notes')} />
        </Field>
      </div>
      {error && <p role="alert" className="mt-4 rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson">{error}</p>}
    </Modal>);

}