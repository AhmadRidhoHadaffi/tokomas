import { Outlet, Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import { Button } from '../ui/Button';

export function CustomerLayout() {
  const { logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/masuk');
  };

  const navItems = [
    { label: 'Beranda', to: '/pelanggan' },
    { label: 'Pesanan', to: '/pelanggan/pesanan' },
    { label: 'Riwayat', to: '/pelanggan/riwayat' },
    { label: 'Notifikasi', to: '/pelanggan/notifikasi' },
    { label: 'Profil', to: '/pelanggan/profil' },
  ];

  return (
    <div className="min-h-screen bg-ivory flex flex-col">
      <header className="bg-charcoal text-ivory px-6 py-4 flex items-center justify-between">
        <h1 className="text-xl font-bold font-serif text-gold">Toko Mas Gembira</h1>
        <nav className="flex items-center space-x-6">
          {navItems.map((item) => (
            <Link
              key={item.to}
              to={item.to}
              className="text-sm text-ivory/80 hover:text-gold transition-colors"
            >
              {item.label}
            </Link>
          ))}
          <Button variant="outlineLight" size="sm" onClick={handleLogout}>
            Keluar
          </Button>
        </nav>
      </header>
      <main className="flex-1 p-8 max-w-6xl mx-auto w-full">
        <Outlet />
      </main>
    </div>
  );
}
