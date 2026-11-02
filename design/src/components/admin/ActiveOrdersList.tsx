import React from 'react';
import { Link } from 'react-router-dom';
import { StatusBadge } from '../ui/Badge';
import { Skeleton } from '../ui/Skeleton';
import { EmptyState } from '../ui/EmptyState';
import type { CustomOrder } from '../../types/customOrder';
import { formatDate } from '../../utils/format';

interface ActiveOrdersListProps {
  orders: CustomOrder[];
  loading?: boolean;
  viewAllTo: string;
}

export function ActiveOrdersList({ orders, loading = false, viewAllTo }: ActiveOrdersListProps) {
  return (
    <section className="flex h-full flex-col rounded-2xl border border-line bg-white">
      <div className="flex items-center justify-between border-b border-line px-5 py-4">
        <h2 className="font-serif text-lg font-semibold text-charcoal">Pesanan Custom Aktif</h2>
        <Link to={viewAllTo} className="text-xs font-medium text-gold-deep hover:underline">
          Lihat semua
        </Link>
      </div>
      {loading ?
      <div className="space-y-4 p-5">
          {Array.from({ length: 4 }).map((_, i) =>
        <div key={i} className="space-y-2">
              <Skeleton className="h-4 w-2/3" />
              <Skeleton className="h-3 w-1/2" />
            </div>
        )}
        </div> :
      orders.length === 0 ?
      <EmptyState title="Tidak ada pesanan aktif" description="Pesanan custom baru akan tampil di sini." /> :

      <ul className="divide-y divide-line">
          {orders.map((order) =>
        <li key={order.id} className="flex items-start justify-between gap-3 px-5 py-3.5">
              <div className="min-w-0">
                <p className="truncate text-sm font-semibold text-charcoal">{order.customer}</p>
                <p className="truncate text-xs text-charcoal/55">{order.product}</p>
                <p className="mt-1 text-[11px] text-charcoal/40">Target {formatDate(order.dueDate)}</p>
              </div>
              <StatusBadge status={order.status} />
            </li>
        )}
        </ul>
      }
    </section>);

}