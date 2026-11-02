import React, { useState } from 'react';
import { CheckCheckIcon, GemIcon, GiftIcon, WalletIcon } from 'lucide-react';
import { Tabs } from '../../components/ui/Tabs';
import { Badge } from '../../components/ui/Badge';
import { Button } from '../../components/ui/Button';
import { EmptyState } from '../../components/ui/EmptyState';
import { useCustomerNotifications } from '../../contexts/CustomerNotificationsContext';
import type { NotificationKind } from '../../types/notification';
import { formatDateTime } from '../../utils/format';

type Filter = 'Semua' | NotificationKind;

const kindMeta = {
  'Status Pesanan': { tone: 'blue', icon: GemIcon, iconClass: 'bg-blue-50 text-blue-700' },
  Pembayaran: { tone: 'orange', icon: WalletIcon, iconClass: 'bg-orange-50 text-orange-700' },
  Promosi: { tone: 'gold', icon: GiftIcon, iconClass: 'bg-gold/15 text-gold-deep' }
} as const;

export function CustomerNotificationsPage() {
  const { notifications, unreadCount, markRead, markAllRead } = useCustomerNotifications();
  const [filter, setFilter] = useState<Filter>('Semua');
  const visible = notifications.filter((n) => filter === 'Semua' || n.kind === filter);

  return (
    <div className="mx-auto max-w-3xl space-y-6">
      <div className="flex items-end justify-between gap-4">
        <div>
          <h1 className="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Notifikasi</h1>
          <p className="mt-1 text-sm text-charcoal/55">{unreadCount > 0 ? `${unreadCount} belum dibaca` : 'Semua notifikasi sudah dibaca'}</p>
        </div>
        <Button variant="light" size="sm" onClick={markAllRead} disabled={unreadCount === 0}>
          <CheckCheckIcon className="h-4 w-4" />
          Tandai semua dibaca
        </Button>
      </div>

      <Tabs
        layoutId="notif-tabs"
        value={filter}
        onChange={setFilter}
        items={[
        { value: 'Semua', label: 'Semua' },
        { value: 'Status Pesanan', label: 'Status Pesanan' },
        { value: 'Pembayaran', label: 'Pembayaran' },
        { value: 'Promosi', label: 'Promosi' }]
        } />
      

      {visible.length === 0 ?
      <div className="rounded-2xl border border-line bg-white">
          <EmptyState title="Tidak ada notifikasi" description="Kabar terbaru tentang pesanan dan promo akan muncul di sini." />
        </div> :

      <ul className="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white">
          {visible.map((n) => {
          const meta = kindMeta[n.kind];
          const Icon = meta.icon;
          return (
            <li key={n.id}>
                <button
                type="button"
                onClick={() => markRead(n.id)}
                className={`flex w-full gap-4 px-5 py-4 text-left transition-colors duration-150 hover:bg-ivory ${n.read ? '' : 'bg-gold/[0.06]'}`}>
                
                  <span className={`flex h-10 w-10 shrink-0 items-center justify-center rounded-full ${meta.iconClass}`}>
                    <Icon className="h-5 w-5" strokeWidth={1.75} />
                  </span>
                  <span className="min-w-0 flex-1">
                    <span className="flex flex-wrap items-center gap-2">
                      <span className={`text-sm ${n.read ? 'font-medium text-charcoal/80' : 'font-semibold text-charcoal'}`}>{n.title}</span>
                      <Badge tone={meta.tone}>{n.kind}</Badge>
                    </span>
                    <span className="mt-1 block text-sm text-charcoal/65">{n.body}</span>
                    <span className="mt-1.5 block text-xs text-charcoal/40">{formatDateTime(n.time)}</span>
                  </span>
                  {!n.read && <span className="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-crimson" aria-label="Belum dibaca" />}
                </button>
              </li>);

        })}
        </ul>
      }
    </div>);

}