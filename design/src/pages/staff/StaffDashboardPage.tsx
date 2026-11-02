import React from 'react';
import { Link } from 'react-router-dom';
import { ArrowRightIcon, GemIcon, PackageIcon, ReceiptTextIcon, RefreshCwIcon, ShoppingCartIcon } from 'lucide-react';
import { StatCard } from '../../components/ui/StatCard';
import { RecentTransactionsTable } from '../../components/admin/RecentTransactionsTable';
import { ActiveOrdersList } from '../../components/admin/ActiveOrdersList';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { transactions } from '../../data/transactions';
import { products } from '../../data/products';
import { customOrders } from '../../data/customOrders';

const quickActions = [
{ label: 'Catat Transaksi Baru', text: 'Pilih pelanggan, tambah produk, simpan nota.', to: '/karyawan/transaksi', icon: ReceiptTextIcon, primary: true },
{ label: 'Tambah Pesanan Custom', text: 'Input pesanan, DP, dan gambar referensi.', to: '/karyawan/pesanan-custom?baru=1', icon: GemIcon, primary: false },
{ label: 'Update Status Pesanan', text: 'Kabari pelanggan tahap pengerjaan terbaru.', to: '/karyawan/pesanan-custom', icon: RefreshCwIcon, primary: false }];


export function StaffDashboardPage() {
  const loading = useSimulatedLoading();
  const todays = transactions.filter((t) => t.date.startsWith('2026-09-25'));
  const activeOrders = customOrders.filter((o) => o.status !== 'Selesai' && o.status !== 'Diambil');
  const totalStock = products.reduce((sum, p) => sum + p.stock, 0);

  return (
    <div className="space-y-6">
      <div>
        <p className="text-sm text-charcoal/55">Jumat, 25 September 2026 · Shift pagi</p>
        <p className="mt-1 font-serif text-2xl font-semibold text-charcoal">Halo, Rina — semangat bertugas!</p>
      </div>

      <div className="grid gap-4 lg:grid-cols-3">
        {quickActions.map(({ label, text, to, icon: Icon, primary }) =>
        <Link
          key={label}
          to={to}
          className={`group flex items-center gap-4 rounded-2xl p-5 transition-[box-shadow,border-color,background-color] duration-150 ${
          primary ?
          'bg-charcoal text-white hover:shadow-gold-glow' :
          'border border-line bg-white hover:border-gold/60'}`
          }>
          
            <span
            className={`flex h-12 w-12 shrink-0 items-center justify-center rounded-xl ${
            primary ? 'bg-gold-gradient text-charcoal' : 'bg-ivory text-gold-dark'}`
            }>
            
              <Icon className="h-5 w-5" strokeWidth={1.75} />
            </span>
            <span className="min-w-0 flex-1">
              <span className={`block font-semibold ${primary ? 'text-white' : 'text-charcoal'}`}>{label}</span>
              <span className={`block text-xs ${primary ? 'text-white/60' : 'text-charcoal/55'}`}>{text}</span>
            </span>
            <ArrowRightIcon className={`h-4 w-4 shrink-0 transition-transform duration-150 group-hover:translate-x-0.5 ${primary ? 'text-gold' : 'text-charcoal/40'}`} />
          </Link>
        )}
      </div>

      <div className="grid gap-4 sm:grid-cols-3">
        <StatCard icon={ShoppingCartIcon} label="Transaksi Hari Ini" value={`${todays.length} transaksi`} />
        <StatCard icon={GemIcon} label="Pesanan Custom Aktif" value={`${activeOrders.length} pesanan`} />
        <StatCard icon={PackageIcon} label="Stok Produk" value={`${totalStock} item`} />
      </div>

      <div className="grid gap-6 xl:grid-cols-3">
        <section className="xl:col-span-2">
          <h2 className="mb-3 font-serif text-lg font-semibold text-charcoal">Transaksi Terbaru</h2>
          <RecentTransactionsTable rows={transactions.slice(0, 6)} loading={loading} />
        </section>
        <ActiveOrdersList orders={activeOrders} loading={loading} viewAllTo="/karyawan/pesanan-custom" />
      </div>
    </div>);

}