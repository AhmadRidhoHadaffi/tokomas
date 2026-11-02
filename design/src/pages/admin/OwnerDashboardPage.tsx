import React from 'react';
import { Link } from 'react-router-dom';
import { GemIcon, PackageIcon, PlusIcon, ShoppingCartIcon, WalletIcon } from 'lucide-react';
import { StatCard } from '../../components/ui/StatCard';
import { Skeleton } from '../../components/ui/Skeleton';
import { buttonClass } from '../../components/ui/Button';
import { SalesLineChart, CashflowBarChart } from '../../components/admin/SalesCharts';
import { RecentTransactionsTable } from '../../components/admin/RecentTransactionsTable';
import { ActiveOrdersList } from '../../components/admin/ActiveOrdersList';
import { useSimulatedLoading } from '../../hooks/useSimulatedLoading';
import { transactions } from '../../data/transactions';
import { products } from '../../data/products';
import { customOrders } from '../../data/customOrders';
import { monthlyCashflow, weeklySales } from '../../data/charts';
import { formatRupiah, transactionTotal } from '../../utils/format';

const TODAY = '2026-09-25';

export function OwnerDashboardPage() {
  const loading = useSimulatedLoading();
  const todays = transactions.filter((t) => t.date.startsWith(TODAY));
  const todaySales = todays.reduce((sum, t) => sum + transactionTotal(t.items, t.discount), 0);
  const totalStock = products.reduce((sum, p) => sum + p.stock, 0);
  const lowStock = products.filter((p) => p.stock <= 3).length;
  const activeOrders = customOrders.filter((o) => o.status !== 'Selesai' && o.status !== 'Diambil');

  return (
    <div className="space-y-6">
      <div className="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
          <p className="text-sm text-charcoal/55">Jumat, 25 September 2026</p>
          <p className="mt-1 font-serif text-2xl font-semibold text-charcoal">Selamat siang, Hj. Rosmaniar</p>
        </div>
        <Link to="/owner/transaksi/baru" className={buttonClass('primary', 'md')}>
          <PlusIcon className="h-4 w-4" />
          Catat Transaksi
        </Link>
      </div>

      <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard featured icon={WalletIcon} label="Total Penjualan Hari Ini" value={formatRupiah(todaySales)} trend={{ value: '12,4%', up: true }} hint="Dibanding kemarin" />
        <StatCard icon={ShoppingCartIcon} label="Jumlah Transaksi" value={`${todays.length} transaksi`} trend={{ value: '1', up: true }} hint="Hari ini" />
        <StatCard icon={PackageIcon} label="Stok Produk" value={`${totalStock} item`} hint={`${lowStock} produk hampir/sudah habis`} />
        <StatCard icon={GemIcon} label="Pesanan Custom Aktif" value={`${activeOrders.length} pesanan`} hint="2 jatuh tempo minggu ini" />
      </div>

      <div className="grid gap-6 lg:grid-cols-5">
        <section className="rounded-2xl border border-line bg-white p-5 lg:col-span-3">
          <div className="flex items-start justify-between">
            <div>
              <h2 className="font-serif text-lg font-semibold text-charcoal">Grafik Penjualan 7 Hari Terakhir</h2>
              <p className="text-sm text-charcoal/50">Total {formatRupiah(weeklySales.reduce((s, d) => s + d.total, 0))}</p>
            </div>
          </div>
          <div className="mt-4 h-72">{loading ? <Skeleton className="h-full w-full rounded-xl" /> : <SalesLineChart data={weeklySales} />}</div>
        </section>
        <section className="rounded-2xl border border-line bg-white p-5 lg:col-span-2">
          <h2 className="font-serif text-lg font-semibold text-charcoal">Pemasukan vs Pengeluaran</h2>
          <p className="text-sm text-charcoal/50">September 2026</p>
          <div className="mt-4 h-72">{loading ? <Skeleton className="h-full w-full rounded-xl" /> : <CashflowBarChart data={monthlyCashflow} />}</div>
        </section>
      </div>

      <div className="grid gap-6 xl:grid-cols-3">
        <section className="xl:col-span-2">
          <div className="mb-3 flex items-center justify-between">
            <h2 className="font-serif text-lg font-semibold text-charcoal">Transaksi Terbaru</h2>
            <Link to="/owner/transaksi" className="text-xs font-medium text-gold-deep hover:underline">
              Lihat semua
            </Link>
          </div>
          <RecentTransactionsTable rows={transactions.slice(0, 6)} loading={loading} />
        </section>
        <ActiveOrdersList orders={activeOrders} loading={loading} viewAllTo="/owner/pesanan-custom" />
      </div>
    </div>);

}