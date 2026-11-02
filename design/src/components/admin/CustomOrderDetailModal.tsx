import React, { useEffect, useState } from 'react';
import { BellRingIcon } from 'lucide-react';
import { Modal } from '../ui/Modal';
import { Button } from '../ui/Button';
import { StatusBadge } from '../ui/Badge';
import { SelectInput } from '../ui/FormField';
import { StatusTimeline } from './StatusTimeline';
import type { CustomOrder, CustomStatus } from '../../types/customOrder';
import { customStatusFlow } from '../../data/customOrders';
import { formatDate, formatRupiah } from '../../utils/format';

interface CustomOrderDetailModalProps {
  order: CustomOrder | null;
  onClose: () => void;
  onUpdateStatus: (id: string, status: CustomStatus) => void;
}

export function CustomOrderDetailModal({ order, onClose, onUpdateStatus }: CustomOrderDetailModalProps) {
  const currentIndex = order ? customStatusFlow.indexOf(order.status) : 0;
  const nextOptions = customStatusFlow.slice(currentIndex + 1);
  const [next, setNext] = useState<CustomStatus | ''>('');

  useEffect(() => {
    setNext(nextOptions[0] ?? '');
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [order?.id, order?.status]);

  return (
    <Modal
      open={order !== null}
      onClose={onClose}
      title={order ? order.product : ''}
      description={order ? `${order.id} · dipesan ${formatDate(order.date)}` : undefined}
      size="xl">
      
      {order &&
      <div className="grid gap-6 lg:grid-cols-5">
          <div className="space-y-5 lg:col-span-3">
            <div className="flex gap-4">
              <img src={order.referenceImage} alt="Gambar referensi pesanan" className="h-28 w-28 shrink-0 rounded-xl object-cover" />
              <div>
                <StatusBadge status={order.status} />
                <p className="mt-2 text-sm font-semibold text-charcoal">{order.customer}</p>
                <p className="text-xs text-charcoal/50">{order.phone}</p>
                <p className="mt-2 text-xs text-charcoal/50">Target selesai {formatDate(order.dueDate)}</p>
              </div>
            </div>
            <dl className="grid grid-cols-2 gap-x-6 gap-y-4 rounded-xl border border-line p-4 text-sm">
              {[
            ['Jenis Custom', order.jewelryType],
            ['Model', order.model],
            ['Nama Ukiran', order.engraving],
            ['Kadar', order.karat],
            ['Berat Estimasi', `${order.weight} gr`],
            ['Harga Estimasi', formatRupiah(order.estimatedPrice)]].
            map(([label, value]) =>
            <div key={label}>
                  <dt className="text-xs text-charcoal/50">{label}</dt>
                  <dd className="mt-0.5 font-medium text-charcoal">{value}</dd>
                </div>
            )}
            </dl>
            <div>
              <p className="text-xs text-charcoal/50">Catatan</p>
              <p className="mt-1 text-sm text-charcoal/80">{order.notes}</p>
            </div>
          </div>

          <div className="space-y-5 lg:col-span-2">
            <div className="rounded-xl bg-ivory p-4 text-sm">
              <div className="flex justify-between">
                <span className="text-charcoal/55">DP dibayar</span>
                <span className="font-medium">{formatRupiah(order.dp)}</span>
              </div>
              <div className="mt-2 flex justify-between border-t border-gold/20 pt-2">
                <span className="font-medium text-charcoal">Sisa pembayaran</span>
                <span className="font-serif text-lg font-semibold text-crimson">{formatRupiah(order.estimatedPrice - order.dp)}</span>
              </div>
            </div>

            <div>
              <p className="mb-3 text-sm font-semibold text-charcoal">Riwayat status</p>
              <StatusTimeline history={order.history} />
            </div>

            {nextOptions.length > 0 ?
          <div className="rounded-xl border border-gold/40 p-4">
                <label htmlFor="next-status" className="text-sm font-semibold text-charcoal">
                  Update Status
                </label>
                <SelectInput id="next-status" className="mt-2" value={next} onChange={(e) => setNext(e.target.value as CustomStatus)}>
                  {nextOptions.map((s) =>
              <option key={s}>{s}</option>
              )}
                </SelectInput>
                <Button className="mt-3 w-full" onClick={() => next && onUpdateStatus(order.id, next)}>
                  Update Status
                </Button>
                <p className="mt-2 flex items-center gap-1.5 text-xs text-charcoal/50">
                  <BellRingIcon className="h-3.5 w-3.5" />
                  Pelanggan otomatis menerima notifikasi.
                </p>
              </div> :

          <p className="rounded-xl bg-gray-50 p-4 text-sm text-charcoal/60">Pesanan telah diambil pelanggan. Tidak ada status lanjutan.</p>
          }
          </div>
        </div>
      }
    </Modal>);

}