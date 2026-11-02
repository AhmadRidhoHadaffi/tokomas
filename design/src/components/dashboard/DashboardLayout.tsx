import React, { useEffect, useState } from 'react';
import { Outlet, useLocation } from 'react-router-dom';
import { AnimatePresence, motion } from 'framer-motion';
import { MenuIcon } from 'lucide-react';
import { SidebarNav } from './SidebarNav';
import { HeaderActions } from './HeaderActions';
import { karyawanNav, ownerNav, sessionUsers } from '../../data/navigation';

interface DashboardLayoutProps {
  role: 'owner' | 'karyawan';
}

const ease = [0.23, 1, 0.32, 1] as const;

export function DashboardLayout({ role }: DashboardLayoutProps) {
  const nav = role === 'owner' ? ownerNav : karyawanNav;
  const user = sessionUsers[role];
  const location = useLocation();
  const [drawerOpen, setDrawerOpen] = useState(false);

  useEffect(() => setDrawerOpen(false), [location.pathname]);

  const current = [...nav].
  sort((a, b) => b.to.length - a.to.length).
  find((item) => location.pathname === item.to || location.pathname.startsWith(`${item.to}/`));
  const title = location.pathname.endsWith('/transaksi/baru') ? 'Catat Transaksi' : current?.label ?? 'Dashboard';

  return (
    <div className="min-h-screen w-full bg-ivory">
      <aside className="fixed inset-y-0 left-0 z-30 hidden w-[76px] flex-col bg-sidebar md:flex lg:w-[240px]">
        <SidebarNav items={nav} compactOnTablet />
      </aside>

      <AnimatePresence>
        {drawerOpen &&
        <div className="fixed inset-0 z-50 lg:hidden">
            <motion.div
            className="absolute inset-0 bg-charcoal/60"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.2, ease }}
            onClick={() => setDrawerOpen(false)} />
          
            <motion.aside
            className="absolute inset-y-0 left-0 flex w-[260px] flex-col bg-sidebar"
            initial={{ x: '-100%' }}
            animate={{ x: 0 }}
            exit={{ x: '-100%' }}
            transition={{ duration: 0.25, ease }}>
            
              <SidebarNav items={nav} onNavigate={() => setDrawerOpen(false)} />
            </motion.aside>
          </div>
        }
      </AnimatePresence>

      <div className="md:pl-[76px] lg:pl-[240px]">
        <header className="sticky top-0 z-20 border-b border-line bg-white/90 backdrop-blur">
          <div className="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
            <button
              type="button"
              onClick={() => setDrawerOpen(true)}
              aria-label="Buka menu"
              className="rounded-lg p-2 text-charcoal/70 transition-colors duration-150 hover:bg-ivory lg:hidden">
              
              <MenuIcon className="h-5 w-5" />
            </button>
            <h1 className="min-w-0 flex-1 truncate font-serif text-xl font-semibold text-charcoal">{title}</h1>
            <HeaderActions name={user.name} roleLabel={user.role} />
          </div>
        </header>
        <main className="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
          <Outlet />
        </main>
      </div>
    </div>);

}