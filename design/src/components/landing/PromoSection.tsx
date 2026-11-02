import React from 'react';
import { Link } from 'react-router-dom';
import { FadeUp } from './FadeUp';
import { buttonClass } from '../ui/Button';
import { promoFeatures } from '../../data/landing';

export function PromoSection() {
  return (
    <section className="relative overflow-hidden bg-crimson py-20 lg:py-24">
      <div className="bg-arabesque absolute inset-0 opacity-40" aria-hidden="true" />
      <div className="relative mx-auto max-w-7xl px-5 lg:px-8">
        <FadeUp className="max-w-2xl">
          <h2 className="font-serif text-3xl font-semibold text-white sm:text-4xl">Selalu Tahu Kabar Pesanan & Promo Terbaru</h2>
          <p className="mt-4 text-white/75">
            Dengan akun pelanggan, semua informasi penting sampai langsung ke Anda — tanpa perlu bolak-balik ke toko.
          </p>
        </FadeUp>
        <div className="mt-12 grid gap-5 md:grid-cols-3">
          {promoFeatures.map(({ icon: Icon, title, text }, i) =>
          <FadeUp key={title} delay={i * 0.05} className="h-full">
              <div className="h-full rounded-2xl border border-white/15 bg-white/[0.06] p-6">
                <Icon className="h-7 w-7 text-gold" strokeWidth={1.5} />
                <h3 className="mt-5 font-serif text-xl font-semibold text-white">{title}</h3>
                <p className="mt-2 text-sm leading-relaxed text-white/70">{text}</p>
              </div>
            </FadeUp>
          )}
        </div>
        <FadeUp className="mt-10">
          <Link to="/daftar" className={buttonClass('primary', 'lg')}>
            Daftar Sekarang
          </Link>
        </FadeUp>
      </div>
    </section>);

}