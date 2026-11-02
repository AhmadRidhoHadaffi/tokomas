import React from 'react';
import { Link, NavLink, Outlet } from 'react-router-dom';
import { BellIcon, LogOutIcon } from 'lucide-react';
import { Logo } from '../brand/Logo';
import { pelangganNav, sessionUsers } from '../../data/navigation';
import { CustomerNotificationsProvider, useCustomerNotifications } from '../../contexts/CustomerNotificationsContext';
import { initials } from '../../utils/format';

export function CustomerLayout() {
  return (
    <CustomerNotificationsProvider>
      <CustomerShell />
    </CustomerNotificationsProvider>);

}

function CustomerShell() {
  const { unreadCount } = useCustomerNotifications();
  const user = sessionUsers.pelanggan;

  return (
    <div className="min-h-screen w-full bg-ivory pb-24 md:pb-0">
      <header className="sticky top-0 z-30 bg-gold-gradient shadow-sm">
        <div className="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
          <Link to="/pelanggan" aria-label="Beranda pelanggan">
            <Logo tone="dark" markClassName="h-10 w-10" />
          </Link>
          <nav aria-label="Menu pelanggan" className="hidden items-center gap-1 md:flex">
            {pelangganNav.map(({ label, to, end }) =>
            <NavLink
              key={to}
              to={to}
              end={end}
              className={({ isActive }) =>
              `rounded-lg px-3.5 py-2 text-sm font-medium transition-colors duration-150 ${
              isActive ? 'bg-charcoal text-gold' : 'text-charcoal/80 hover:bg-charcoal/10 hover:text-charcoal'}`

              }>
              
                {label}
              </NavLink>
            )}
          </nav>
          <div className="flex items-center gap-2">
            <Link
              to="/pelanggan/notifikasi"
              aria-label={`Notifikasi, ${unreadCount} belum dibaca`}
              className="relative rounded-lg p-2 text-charcoal transition-colors duration-150 hover:bg-charcoal/10">
              
              <BellIcon className="h-5 w-5" strokeWidth={1.75} />
              {unreadCount > 0 &&
              <span className="absolute right-0.5 top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-crimson px-1 text-[10px] font-semibold text-white">
                  {unreadCount}
                </span>
              }
            </Link>
            <Link
              to="/pelanggan/profil"
              aria-label="Profil"
              className="flex h-9 w-9 items-center justify-center rounded-full bg-charcoal text-xs font-semibold text-gold">
              
              {initials(user.name)}
            </Link>
            <Link
              to="/masuk"
              aria-label="Keluar"
              className="hidden rounded-lg p-2 text-charcoal/75 transition-colors duration-150 hover:bg-charcoal/10 md:block">
              
              <LogOutIcon className="h-5 w-5" strokeWidth={1.75} />
            </Link>
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:py-10">
        <Outlet />
      </main>

      <nav
        aria-label="Navigasi bawah"
        className="fixed inset-x-0 bottom-0 z-30 border-t border-line bg-white pb-[env(safe-area-inset-bottom)] md:hidden">
        
        <ul className="grid grid-cols-5">
          {pelangganNav.map(({ label, to, icon: Icon, end }) =>
          <li key={to}>
              <NavLink
              to={to}
              end={end}
              className={({ isActive }) =>
              `relative flex flex-col items-center gap-1 py-2.5 text-[11px] font-medium transition-colors duration-150 ${
              isActive ? 'text-gold-dark' : 'text-charcoal/50'}`

              }>
              
                {({ isActive }) =>
              <>
                    {isActive && <span className="absolute inset-x-5 top-0 h-0.5 rounded-full bg-gold" aria-hidden="true" />}
                    <span className="relative">
                      <Icon className="h-5 w-5" strokeWidth={isActive ? 2 : 1.75} />
                      {label === 'Notifikasi' && unreadCount > 0 &&
                  <span className="absolute -right-1.5 -top-1 h-2 w-2 rounded-full bg-crimson" />
                  }
                    </span>
                    {label}
                  </>
              }
              </NavLink>
            </li>
          )}
        </ul>
      </nav>
    </div>);

}