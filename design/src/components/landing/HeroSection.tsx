import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { buttonClass } from '../ui/Button';
import { trustBadges } from '../../data/landing';
import { images } from '../../data/images';

const ease = [0.23, 1, 0.32, 1] as const;

export function HeroSection() {
  return (
    <section id="beranda" className="relative overflow-hidden bg-charcoal pb-14 pt-32 lg:pt-40">
      <div className="bg-arabesque absolute inset-0" aria-hidden="true" />
      <div className="relative mx-auto max-w-7xl px-5 lg:px-8">
        <div className="grid items-center gap-12 lg:grid-cols-12">
          <motion.div
            className="lg:col-span-7"
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.3, ease }}>
            
            <h1 className="font-serif text-4xl font-semibold leading-[1.1] text-white sm:text-5xl lg:text-[64px]">
              Perhiasan <span className="text-gold">Emas &amp; Perak</span> Terbaik di Padang
            </h1>
            <p className="mt-6 max-w-xl text-base leading-relaxed text-white/70 sm:text-lg">
              Belanja online, pesan custom, dan pantau pesanan Anda — semua dalam satu platform.
            </p>
            <div className="mt-9 flex flex-col gap-3 sm:flex-row">
              <a href="#produk" className={buttonClass('primary', 'lg')}>
                Lihat Produk Kami
              </a>
              <Link to="/daftar" className={buttonClass('outlineLight', 'lg')}>
                Buat Akun Gratis
              </Link>
            </div>
          </motion.div>

          <motion.div
            className="relative mx-auto w-full max-w-sm lg:col-span-5 lg:max-w-none"
            initial={{ opacity: 0, scale: 0.96 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.3, delay: 0.08, ease }}>
            
            <div className="rounded-t-[999px] border border-gold/40 p-3">
              <img
                src={images.cincinEmas}
                alt="Cincin emas filigree khas Minang di atas beludru gelap"
                className="aspect-[4/5] w-full rounded-t-[999px] object-cover" />
              
            </div>
            <div className="absolute -bottom-5 left-4 right-4 rounded-xl border border-gold/30 bg-charcoal/95 px-5 py-4 backdrop-blur sm:left-auto sm:right-[-12px] sm:w-64">
              <p className="text-xs text-white/55">Harga emas 22K hari ini</p>
              <p className="mt-0.5 font-serif text-xl font-semibold text-gold">Rp 1.650.000 / gr</p>
              <p className="mt-1 text-[11px] text-white/40">Diperbarui 25 Sep 2026, 09.00 WIB</p>
            </div>
          </motion.div>
        </div>

        <ul className="mt-20 grid grid-cols-2 gap-x-6 gap-y-5 border-t border-white/10 pt-8 sm:grid-cols-3 lg:grid-cols-5">
          {trustBadges.map(({ icon: Icon, label }) =>
          <li key={label} className="flex items-center gap-3">
              <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-gold/40 text-gold">
                <Icon className="h-4 w-4" strokeWidth={1.75} />
              </span>
              <span className="text-sm text-white/80">{label}</span>
            </li>
          )}
        </ul>
      </div>
    </section>);

}