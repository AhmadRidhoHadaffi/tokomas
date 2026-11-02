import React, { useState } from 'react';
import { SendIcon, PlusIcon } from 'lucide-react';
import { toast } from 'sonner';
import { SortableTable, type Column } from '../../components/ui/SortableTable';
import { Tabs } from '../../components/ui/Tabs';
import { Button } from '../../components/ui/Button';
import { Badge } from '../../components/ui/Badge';
import { Modal } from '../../components/ui/Modal';
import { Field, TextArea, TextInput } from '../../components/ui/FormField';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { promotions as initialPromotions, sentNotifications } from '../../data/notifications';
import { customers } from '../../data/people';
import type { Promotion, SentNotification } from '../../types/notification';
import { formatDateTime } from '../../utils/format';

const statusTone = { Terkirim: 'green', Terjadwal: 'blue', Draf: 'grey' } as const;
const kindTone = { 'Status Pesanan': 'blue', Pembayaran: 'orange', Promosi: 'gold' } as const;

export function PromotionsPage() {
  const loading = useSimulatedLoading();
  const [tab, setTab] = useState<'promo' | 'sent'>('promo');
  const [promos, setPromos] = useState<Promotion[]>(initialPromotions);
  const [open, setOpen] = useState(false);
  const [title, setTitle] = useState('');
  const [message, setMessage] = useState('');
  const [targetAll, setTargetAll] = useState(true);
  const [picked, setPicked] = useState<string[]>([]);
  const [scheduleLater, setScheduleLater] = useState(false);
  const [schedule, setSchedule] = useState('2026-09-27T09:00');
  const [error, setError] = useState<string | null>(null);

  const reset = () => {
    setTitle('');
    setMessage('');
    setTargetAll(true);
    setPicked([]);
    setScheduleLater(false);
    setError(null);
  };

  const send = () => {
    if (!title.trim() || !message.trim()) {
      setError('Judul dan isi pesan wajib diisi.');
      return;
    }
    if (!targetAll && picked.length === 0) {
      setError('Pilih minimal satu pelanggan.');
      return;
    }
    const promo: Promotion = {
      id: `PRM-0${promos.length + 11}`,
      title: title.trim(),
      message: message.trim(),
      target: targetAll ? 'Semua Pelanggan' : `${picked.length} pelanggan terpilih`,
      recipients: targetAll ? 248 : picked.length,
      sendDate: scheduleLater ? `${schedule}:00` : new Date().toISOString(),
      status: scheduleLater ? 'Terjadwal' : 'Terkirim'
    };
    setPromos((p) => [promo, ...p]);
    toast.success(scheduleLater ? 'Promosi dijadwalkan' : 'Promosi terkirim', { description: `${promo.recipients} penerima` });
    setOpen(false);
    reset();
  };

  const promoColumns: Column<Promotion>[] = [
  {
    key: 'title',
    header: 'Judul',
    sortValue: (p) => p.title,
    render: (p) =>
    <span className="block max-w-sm">
          <span className="block truncate font-medium text-charcoal">{p.title}</span>
          <span className="block truncate text-xs text-charcoal/45">{p.message}</span>
        </span>

  },
  { key: 'target', header: 'Target Pelanggan', render: (p) => p.target },
  { key: 'recipients', header: 'Penerima', align: 'right', sortValue: (p) => p.recipients, render: (p) => p.recipients },
  { key: 'date', header: 'Tanggal Kirim', sortValue: (p) => p.sendDate, render: (p) => formatDateTime(p.sendDate) },
  { key: 'status', header: 'Status', sortValue: (p) => p.status, render: (p) => <Badge tone={statusTone[p.status]} dot>{p.status}</Badge> }];


  const sentColumns: Column<SentNotification>[] = [
  { key: 'recipient', header: 'Penerima', sortValue: (n) => n.recipient, render: (n) => <span className="font-medium text-charcoal">{n.recipient}</span> },
  { key: 'kind', header: 'Jenis', sortValue: (n) => n.kind, render: (n) => <Badge tone={kindTone[n.kind]}>{n.kind}</Badge> },
  { key: 'content', header: 'Isi', render: (n) => <span className="block max-w-xs truncate">{n.content}</span> },
  { key: 'time', header: 'Waktu', sortValue: (n) => n.time, render: (n) => formatDateTime(n.time) },
  { key: 'delivered', header: 'Status Terkirim', render: (n) => n.delivered ? <Badge tone="green" dot>Terkirim</Badge> : <Badge tone="yellow" dot>Menunggu</Badge> }];


  return (
    <div className="space-y-5">
      <div className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <Tabs
          layoutId="promo-tabs"
          value={tab}
          onChange={setTab}
          items={[
          { value: 'promo', label: 'Promosi', count: promos.length },
          { value: 'sent', label: 'Notifikasi Terkirim', count: sentNotifications.length }]
          } />
        
        {tab === 'promo' &&
        <Button onClick={() => setOpen(true)}>
            <PlusIcon className="h-4 w-4" />
            Buat Promosi
          </Button>
        }
      </div>

      {tab === 'promo' ?
      <SortableTable columns={promoColumns} rows={promos} rowKey={(p) => p.id} loading={loading} emptyTitle="Belum ada promosi" emptyDescription="Buat promosi pertama untuk pelanggan setia Anda." /> :

      <SortableTable columns={sentColumns} rows={sentNotifications} rowKey={(n) => n.id} loading={loading} emptyTitle="Belum ada notifikasi terkirim" />
      }

      <Modal
        open={open}
        onClose={() => setOpen(false)}
        title="Buat Promosi"
        description="Pesan dikirim sebagai notifikasi ke akun pelanggan."
        size="lg"
        footer={
        <>
            <Button variant="light" onClick={() => setOpen(false)}>Batal</Button>
            <Button onClick={send}>
              <SendIcon className="h-4 w-4" />
              {scheduleLater ? 'Jadwalkan' : 'Kirim'}
            </Button>
          </>
        }>
        
        <div className="space-y-5">
          <Field label="Judul Promosi" htmlFor="pr-title">
            <TextInput id="pr-title" placeholder="Diskon Ongkos Bikin 20%" value={title} onChange={(e) => setTitle(e.target.value)} />
          </Field>
          <Field label="Isi Pesan" htmlFor="pr-msg" hint={`${message.length}/300 karakter`}>
            <TextArea id="pr-msg" maxLength={300} placeholder="Tulis isi promosi…" value={message} onChange={(e) => setMessage(e.target.value)} />
          </Field>

          <fieldset>
            <legend className="mb-2 text-sm font-medium text-charcoal/80">Target Pelanggan</legend>
            <div className="grid gap-2 sm:grid-cols-2">
              {[
              { value: true, label: 'Semua pelanggan', text: '248 pelanggan terdaftar' },
              { value: false, label: 'Pilih pelanggan', text: 'Centang dari daftar' }].
              map((opt) =>
              <label key={opt.label} className={`flex cursor-pointer flex-col rounded-xl border p-3 transition-colors duration-150 ${targetAll === opt.value ? 'border-gold bg-gold/10' : 'border-line hover:border-gold/50'}`}>
                  <input type="radio" name="target" className="sr-only" checked={targetAll === opt.value} onChange={() => setTargetAll(opt.value)} />
                  <span className="text-sm font-semibold text-charcoal">{opt.label}</span>
                  <span className="text-xs text-charcoal/50">{opt.text}</span>
                </label>
              )}
            </div>
            {!targetAll &&
            <ul className="scrollbar-thin mt-3 max-h-44 divide-y divide-line overflow-y-auto rounded-xl border border-line">
                {customers.map((c) =>
              <li key={c.id}>
                    <label className="flex cursor-pointer items-center gap-3 px-3 py-2.5 text-sm hover:bg-ivory">
                      <input
                    type="checkbox"
                    checked={picked.includes(c.id)}
                    onChange={(e) => setPicked((p) => e.target.checked ? [...p, c.id] : p.filter((id) => id !== c.id))}
                    className="h-4 w-4 rounded border-line accent-[#B8860B]" />
                  
                      <span className="flex-1 text-charcoal">{c.name}</span>
                      <span className="text-xs text-charcoal/45">{c.phone}</span>
                    </label>
                  </li>
              )}
              </ul>
            }
          </fieldset>

          <fieldset>
            <legend className="mb-2 text-sm font-medium text-charcoal/80">Jadwal Kirim</legend>
            <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
              <label className="flex items-center gap-2 text-sm">
                <input type="radio" name="schedule" checked={!scheduleLater} onChange={() => setScheduleLater(false)} className="accent-[#B8860B]" />
                Kirim sekarang
              </label>
              <label className="flex items-center gap-2 text-sm">
                <input type="radio" name="schedule" checked={scheduleLater} onChange={() => setScheduleLater(true)} className="accent-[#B8860B]" />
                Jadwalkan
              </label>
              {scheduleLater &&
              <TextInput type="datetime-local" aria-label="Waktu kirim" value={schedule} onChange={(e) => setSchedule(e.target.value)} className="sm:w-60" />
              }
            </div>
          </fieldset>
          {error && <p role="alert" className="rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson">{error}</p>}
        </div>
      </Modal>
    </div>);

}