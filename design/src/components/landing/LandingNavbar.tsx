import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { AnimatePresence, motion } from 'framer-motion';
import { MenuIcon, XIcon } from 'lucide-react';
import { Logo } from '../brand/Logo';
import { buttonClass } from '../ui/Button';
import { navLinks } from '../../data/landing';

export function LandingNavbar() {
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 24);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  const solid = scrolled || open;

  return (
    <header
      className={`fixed inset-x-0 top-0 z-50 transition-[background-color,box-shadow] duration-200 ease-out ${
      solid ? 'bg-charcoal/95 shadow-lg shadow-black/20 backdrop-blur' : 'bg-transparent'}`
      }>
      
      <div className="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 lg:px-8">
        <a href="#beranda" aria-label="Toko Mas Gembira — Beranda">
          <Logo tone="light" />
        </a>

        <nav aria-label="Navigasi utama" className="hidden items-center gap-8 lg:flex">
          {navLinks.map((link) =>
          <a
            key={link.href}
            href={link.href}
            className="text-sm font-medium text-white/80 transition-colors duration-150 hover:text-gold">
            
              {link.label}
            </a>
          )}
        </nav>

        <div className="hidden items-center gap-3 lg:flex">
          <Link to="/masuk" className={buttonClass('outlineDark', 'sm', 'px-5')}>
            Masuk
          </Link>
          <Link to="/daftar" className={buttonClass('primary', 'sm', 'px-5')}>
            Daftar
          </Link>
        </div>

        <button
          type="button"
          onClick={() => setOpen((v) => !v)}
          aria-expanded={open}
          aria-label={open ? 'Tutup menu' : 'Buka menu'}
          className="rounded-lg p-2 text-white transition-colors duration-150 hover:bg-white/10 lg:hidden">
          
          {open ? <XIcon className="h-6 w-6" /> : <MenuIcon className="h-6 w-6" />}
        </button>
      </div>

      <AnimatePresence>
        {open &&
        <motion.nav
          aria-label="Navigasi seluler"
          initial={{ opacity: 0, y: -8 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: -8 }}
          transition={{ duration: 0.2, ease: [0.23, 1, 0.32, 1] }}
          className="border-t border-white/10 bg-charcoal px-5 pb-6 pt-2 lg:hidden">
          
            {navLinks.map((link) =>
          <a
            key={link.href}
            href={link.href}
            onClick={() => setOpen(false)}
            className="block border-b border-white/5 py-3.5 text-[15px] text-white/85 hover:text-gold">
            
                {link.label}
              </a>
          )}
            <div className="mt-5 grid grid-cols-2 gap-3">
              <Link to="/masuk" className={buttonClass('outlineDark', 'md')}>
                Masuk
              </Link>
              <Link to="/daftar" className={buttonClass('primary', 'md')}>
                Daftar
              </Link>
            </div>
          </motion.nav>
        }
      </AnimatePresence>
    </header>);

}