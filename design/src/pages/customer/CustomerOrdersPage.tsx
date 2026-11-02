import React, { useState } from 'react';
import { ImageIcon } from 'lucide-react';
import { StatusBadge } from '../../components/ui/Badge';
import { Modal } from '../../components/ui/Modal';
import { Button, buttonClass } from '../../components/ui/Button';
import { EmptyState } from '../../components/ui/EmptyState';
import { Skeleton } from '../../components/ui/Skeleton';
import { OrderProgress } from '../../components/customer/OrderProgress';
import { StatusTimeline } from '../../components/admin/StatusTimeline';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { customOrders } from '../../data/customOrders';
import { sessionUsers } from '../../data/navigation';
import { store } from '../../data/images';
import type { CustomOrder } from '../../types/customOrder';
import { formatDate, formatRupiah } from '../../utils/format';

export function CustomerOrdersPage() {
  const loading = useSimulatedLoading();
  const [selected, setSelected] = useState<CustomOrder | null>(null);
  const orders = customOrders.filter((o) => o.customer === sessionUsers.pelanggan.name);

  return (
    <div className="space-y-6">
      <div className="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
        <div>
          <h1 className="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Pesanan Custom</h1>
          <p className="mt-1 text-sm text-charcoal/55">Pantau setiap tahap pembuatan perhiasan Anda.</p>
        </div>
        <a href={store.whatsapp} target="_blank" rel="noreferrer" className={buttonClass('primary', 'md')}>
          Ajukan Pesanan Baru
        </a>
      </div>

      {loading ?
      <div className="space-y-4">
          {[0, 1].map((i) =>
        <Skeleton key={i} className="h-52 rounded-2xl" />
        )}
        </div> :
      orders.length === 0 ?
      <div className="rounded-2xl border border-line bg-white">
          <EmptyState title="Belum ada pesanan custom" description="Wujudkan perhiasan impian Anda — ajukan pesanan pertama." />
        </div> :

      <ul className="space-y-4">
          {orders.map((o) =>
        <li key={o.id} className="rounded-2xl border border-line bg-white p-5 sm:p-6">
              <div className="flex items-start gap-4">
                <img src={o.referenceImage} alt="" className="h-16 w-16 shrink-0 rounded-xl object-cover" />
                <div className="min-w-0 flex-1">
                  <div className="flex flex-wrap items-center gap-2">
                    <p className="font-mono text-xs text-charcoal/50">{o.id}</p>
                    <StatusBadge status={o.status} />
                  </div>
                  <h2 className="mt-1 font-serif text-lg font-semibold text-charcoal">{o.product}</h2>
                  <p className="text-xs text-charcoal/50">Dipesan {formatDate(o.date)} · Target {formatDate(o.dueDate)}</p>
                </div>
              </div>

              <div className="mt-6">
                <OrderProgress status={o.status} />
              </div>

              <div className="mt-6 flex flex-col gap-4 border-t border-line pt-5 sm:flex-row sm:items-center sm:justify-between">
                <dl className="flex gap-8 text-sm">
                  <div>
                    <dt className="text-xs text-charcoal/50">DP</dt>
                    <dd className="font-medium text-charcoal">{formatRupiah(o.dp)}</dd>
                  </div>
                  <div>
                    <dt className="text-xs text-charcoal/50">Sisa</dt>
                    <dd className="font-semibold text-crimson">{formatRupiah(o.estimatedPrice - o.dp)}</dd>
                  </div>
                </dl>
                <Button variant="light" onClick={() => setSelected(o)}>
                  Lihat Detail
                </Button>
              </div>
            </li>
        )}
        </ul>
      }

      <Modal open={selected !== null} onClose={() => setSelected(null)} title={selected?.product ?? ''} description={selected?.id} size="lg">
        {selected &&
        <div className="grid gap-6 sm:grid-cols-2">
            <div>
              <p className="mb-2 flex items-center gap-1.5 text-xs font-medium text-charcoal/55">
                <ImageIcon className="h-3.5 w-3.5" />
                Foto referensi
              </p>
              <img src={selected.referenceImage} alt="Foto referensi pesanan" className="aspect-square w-full rounded-xl object-cover" />
            </div>
            <div className="space-y-5">
              <dl className="space-y-3 text-sm">
                {[
              ['Model', selected.model],
              ['Nama Ukiran', selected.engraving],
              ['Kadar', selected.karat],
              ['Berat Estimasi', `${selected.weight} gr`]].
              map(([label, value]) =>
              <div key={label} className="flex justify-between gap-4">
                    <dt className="text-charcoal/55">{label}</dt>
                    <dd className="text-right font-medium text-charcoal">{value}</dd>
                  </div>
              )}
              </dl>
              <div className="rounded-xl bg-ivory p-4 text-sm">
                <div className="flex justify-between">
                  <span className="text-charcoal/55">DP dibayar</span>
                  <span className="font-medium">{formatRupiah(selected.dp)}</span>
                </div>
                <div className="mt-2 flex justify-between border-t border-gold/20 pt-2">
                  <span className="font-medium">Sisa pembayaran</span>
                  <span className="font-serif text-lg font-semibold text-crimson">{formatRupiah(selected.estimatedPrice - selected.dp)}</span>
                </div>
              </div>
              <div>
                <p className="text-xs text-charcoal/50">Catatan</p>
                <p className="mt-1 text-sm text-charcoal/80">{selected.notes}</p>
              </div>
              <div>
                <p className="mb-3 text-sm font-semibold text-charcoal">Riwayat status</p>
                <StatusTimeline history={selected.history} />
              </div>
            </div>
          </div>
        }
      </Modal>
    </div>);

}