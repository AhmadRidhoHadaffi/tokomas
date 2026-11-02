import React from 'react';
import { Logo } from '../brand/Logo';
import { navLinks } from '../../data/landing';

export function LandingFooter() {
  return (
    <footer className="bg-[#141414]">
      <div className="h-px bg-gold/50" aria-hidden="true" />
      <div className="mx-auto flex max-w-7xl flex-col gap-8 px-5 py-12 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <Logo tone="light" showTagline markClassName="h-12 w-12" />
        <nav aria-label="Navigasi footer" className="flex flex-wrap gap-x-7 gap-y-3">
          {navLinks.map((link) =>
          <a key={link.href} href={link.href} className="text-sm text-white/65 transition-colors duration-150 hover:text-gold">
              {link.label}
            </a>
          )}
        </nav>
      </div>
      <div className="mx-auto max-w-7xl border-t border-white/10 px-5 py-6 text-xs text-white/45 lg:px-8">
        © 2026 Toko Mas Gembira · Pasar Raya Padang, Sumatera Barat. Hak cipta dilindungi.
      </div>
    </footer>);

}