import React from 'react';
import { Link } from 'react-router-dom';
import { FadeUp } from './FadeUp';
import { buttonClass } from '../ui/Button';

export function RegisterBanner() {
  return (
    <section className="bg-ivory pb-20 lg:pb-28">
      <div className="mx-auto max-w-7xl px-5 lg:px-8">
        <FadeUp>
          <div className="relative overflow-hidden rounded-2xl bg-gold-gradient px-6 py-12 sm:px-12 lg:flex lg:items-center lg:justify-between lg:py-14">
            <div className="bg-arabesque absolute inset-0 opacity-50 mix-blend-multiply" aria-hidden="true" />
            <div className="relative max-w-xl">
              <h2 className="font-serif text-3xl font-semibold text-charcoal sm:text-4xl">Bergabung Sekarang dan Mulai Belanja</h2>
              <p className="mt-3 text-charcoal/75">Gratis selamanya. Simpan riwayat belanja dan dapatkan promo khusus pelanggan.</p>
            </div>
            <div className="relative mt-8 flex flex-col gap-3 sm:flex-row lg:mt-0">
              <Link to="/daftar" className={buttonClass('dark', 'lg')}>
                Buat Akun Gratis
              </Link>
              <Link
                to="/masuk"
                className={buttonClass('ghost', 'lg', 'border border-charcoal/70 !text-charcoal hover:!bg-charcoal/10')}>
                
                Masuk ke Akun
              </Link>
            </div>
          </div>
        </FadeUp>
      </div>
    </section>);

}