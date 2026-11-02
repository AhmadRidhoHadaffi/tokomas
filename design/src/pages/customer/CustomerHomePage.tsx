import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { AnimatePresence, motion } from 'framer-motion';
import { BellIcon, ChevronRightIcon, GemIcon, GiftIcon, XIcon } from 'lucide-react';
import { StatusBadge } from '../../components/ui/Badge';
import { OrderProgress } from '../../components/customer/OrderProgress';
import { PaymentBadge } from '../../components/admin/PaymentBadge';
import { useCustomerNotifications } from '../../contexts/CustomerNotificationsContext';
import { transactions } from '../../data/transactions';
import { customOrders } from '../../data/customOrders';
import { promotions } from '../../data/notifications';
import { sessionUsers } from '../../data/navigation';
import { formatDate, formatRupiah, transactionTotal } from '../../utils/format';

export function CustomerHomePage() {
  const user = sessionUsers.pelanggan;
  const { unreadCount } = useCustomerNotifications();
  const [promoVisible, setPromoVisible] = useState(true);
  const myTrx = transactions.filter((t) => t.customer === user.name).slice(0, 3);
  const myOrders = customOrders.filter((o) => o.customer === user.name);
  const activeOrders = myOrders.filter((o) => o.status !== 'Diambil');
  const featured = activeOrders[0];
  const promo = promotions[0];

  return (
    <div className="space-y-6">
      <section className="grid gap-4 lg:grid-cols-3">
        <div className="relative overflow-hidden rounded-2xl bg-charcoal p-6 text-white lg:col-span-2">
          <div className="bg-arabesque absolute inset-0 opacity-60" aria-hidden="true" />
          <div className="relative">
            <h1 className="font-serif text-2xl font-semibold sm:text-3xl">Selamat datang, {user.name.split(' ')[0]}!</h1>
            <p className="mt-1 text-sm text-white/65">Senang melihat Anda kembali di Toko Mas Gembira.</p>
            {featured &&
            <div className="mt-6 rounded-xl bg-white p-4 text-charcoal">
                <div className="mb-4 flex items-center justify-between gap-3">
                  <div className="min-w-0">
                    <p className="text-xs text-charcoal/50">Pesanan terbaru · {featured.id}</p>
                    <p className="truncate font-semibold">{featured.product}</p>
                  </div>
                  <Link to="/pelanggan/pesanan" className="shrink-0 text-xs font-medium text-gold-deep hover:underline">
                    Lacak
                  </Link>
                </div>
                <OrderProgress status={featured.status} />
              </div>
            }
          </div>
        </div>

        <div className="grid grid-cols-2 gap-4 lg:grid-cols-1">
          <Link to="/pelanggan/pesanan" className="flex flex-col justify-between rounded-2xl border border-line bg-white p-5 transition-colors duration-150 hover:border-gold/60">
            <GemIcon className="h-6 w-6 text-gold-dark" strokeWidth={1.5} />
            <div className="mt-4">
              <p className="font-serif text-3xl font-semibold text-charcoal">{activeOrders.length}</p>
              <p className="text-sm text-charcoal/55">Pesanan Custom Aktif</p>
            </div>
          </Link>
          <Link to="/pelanggan/notifikasi" className="flex flex-col justify-between rounded-2xl border border-line bg-white p-5 transition-colors duration-150 hover:border-gold/60">
            <BellIcon className="h-6 w-6 text-gold-dark" strokeWidth={1.5} />
            <div className="mt-4">
              <p className="font-serif text-3xl font-semibold text-charcoal">{unreadCount}</p>
              <p className="text-sm text-charcoal/55">Notifikasi Belum Dibaca</p>
            </div>
          </Link>
        </div>
      </section>

      <AnimatePresence initial={false}>
        {promoVisible &&
        <motion.section
          initial={{ opacity: 0, height: 0 }}
          animate={{ opacity: 1, height: 'auto' }}
          exit={{ opacity: 0, height: 0 }}
          transition={{ duration: 0.22, ease: [0.23, 1, 0.32, 1] }}
          className="overflow-hidden"
          aria-label="Promosi terbaru">
          
            <div className="relative flex items-start gap-4 rounded-2xl bg-gold-gradient p-5 pr-12 sm:items-center">
              <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-charcoal text-gold">
                <GiftIcon className="h-5 w-5" strokeWidth={1.75} />
              </span>
              <div>
                <p className="font-serif text-lg font-semibold text-charcoal">{promo.title}</p>
                <p className="text-sm text-charcoal/75">{promo.message}</p>
              </div>
              <button
              type="button"
              onClick={() => setPromoVisible(false)}
              aria-label="Tutup promosi"
              className="absolute right-3 top-3 rounded-full p-1.5 text-charcoal/70 transition-colors duration-150 hover:bg-charcoal/10 hover:text-charcoal">
              
                <XIcon className="h-4 w-4" />
              </button>
            </div>
          </motion.section>
        }
      </AnimatePresence>

      <div className="grid gap-6 lg:grid-cols-2">
        <section className="rounded-2xl border border-line bg-white">
          <div className="flex items-center justify-between border-b border-line px-5 py-4">
            <h2 className="font-serif text-lg font-semibold text-charcoal">Transaksi Terakhir</h2>
            <Link to="/pelanggan/riwayat" className="text-xs font-medium text-gold-deep hover:underline">Lihat semua</Link>
          </div>
          <ul className="divide-y divide-line">
            {myTrx.map((t) =>
            <li key={t.id} className="flex items-center justify-between gap-3 px-5 py-3.5">
                <div className="min-w-0">
                  <p className="truncate text-sm font-medium text-charcoal">{t.items.map((i) => i.name).join(', ')}</p>
                  <p className="text-xs text-charcoal/45">{formatDate(t.date)}</p>
                </div>
                <div className="flex shrink-0 flex-col items-end gap-1">
                  <span className="text-sm font-semibold">{formatRupiah(transactionTotal(t.items, t.discount))}</span>
                  <PaymentBadge method={t.method} />
                </div>
              </li>
            )}
          </ul>
        </section>

        <section className="rounded-2xl border border-line bg-white">
          <div className="flex items-center justify-between border-b border-line px-5 py-4">
            <h2 className="font-serif text-lg font-semibold text-charcoal">Pesanan Custom Terakhir</h2>
            <Link to="/pelanggan/pesanan" className="text-xs font-medium text-gold-deep hover:underline">Lihat semua</Link>
          </div>
          <ul className="divide-y divide-line">
            {myOrders.slice(0, 3).map((o) =>
            <li key={o.id}>
                <Link to="/pelanggan/pesanan" className="flex items-center gap-3 px-5 py-3.5 transition-colors duration-150 hover:bg-ivory">
                  <img src={o.referenceImage} alt="" className="h-11 w-11 rounded-lg object-cover" />
                  <div className="min-w-0 flex-1">
                    <p className="truncate text-sm font-medium text-charcoal">{o.product}</p>
                    <p className="text-xs text-charcoal/45">{o.id} · {formatDate(o.date)}</p>
                  </div>
                  <StatusBadge status={o.status} />
                  <ChevronRightIcon className="h-4 w-4 text-charcoal/30" />
                </Link>
              </li>
            )}
          </ul>
        </section>
      </div>
    </div>);

}