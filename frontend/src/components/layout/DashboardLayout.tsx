import { Outlet, Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import { Button } from '../ui/Button';

interface DashboardLayoutProps {
  role: 'owner' | 'karyawan';
}

export function DashboardLayout({ role }: DashboardLayoutProps) {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/masuk');
  };

  const navItems = role === 'owner' ? [
    { label: 'Dashboard', to: '/owner' },
    { label: 'Produk & Stok', to: '/owner/produk' },
    { label: 'Transaksi Penjualan', to: '/owner/transaksi' },
    { label: 'Pembukuan', to: '/owner/pembukuan' },
    { label: 'Pesanan Custom', to: '/owner/pesanan-custom' },
    { label: 'Pelanggan', to: '/owner/pelanggan' },
    { label: 'Promosi & Notifikasi', to: '/owner/promosi' },
    { label: 'Manajemen Karyawan', to: '/owner/karyawan' },
    { label: 'Laporan', to: '/owner/laporan' },
    { label: 'Pengaturan', to: '/owner/pengaturan' },
  ] : [
    { label: 'Dashboard', to: '/karyawan' },
    { label: 'Produk & Stok', to: '/karyawan/produk' },
    { label: 'Catat Transaksi', to: '/karyawan/transaksi' },
    { label: 'Pembukuan', to: '/karyawan/pembukuan' },
    { label: 'Pesanan Custom', to: '/karyawan/pesanan-custom' },
    { label: 'Pelanggan', to: '/karyawan/pelanggan' },
    { label: 'Promosi & Notifikasi', to: '/karyawan/promosi' },
  ];

  return (
    <div className="flex h-screen bg-ivory">
      <aside className="w-64 bg-sidebar text-ivory flex flex-col">
        <div className="p-6 border-b border-sidebar/50">
          <h1 className="text-xl font-bold font-serif text-gold">Toko Mas Gembira</h1>
          <p className="text-xs text-ivory/60 mt-1 capitalize">{role} Panel</p>
        </div>
        <nav className="flex-1 p-4 space-y-1 overflow-y-auto">
          {navItems.map((item) => (
            <Link
              key={item.to}
              to={item.to}
              className="block px-4 py-2.5 rounded text-sm text-ivory/80 hover:bg-white/10 hover:text-ivory transition-colors"
            >
              {item.label}
            </Link>
          ))}
        </nav>
        <div className="p-4 border-t border-sidebar/50">
          <p className="text-sm font-medium">{user?.name}</p>
          <p className="text-xs text-ivory/60 mb-3">{user?.email}</p>
          <Button variant="danger" size="sm" className="w-full" onClick={handleLogout}>
            Keluar
          </Button>
        </div>
      </aside>
      <main className="flex-1 overflow-y-auto p-8">
        <Outlet />
      </main>
    </div>
  );
}
