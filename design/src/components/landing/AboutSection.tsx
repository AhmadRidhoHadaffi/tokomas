import React from 'react';
import { FadeUp } from './FadeUp';
import { aboutHighlights } from '../../data/landing';
import { images } from '../../data/images';

export function AboutSection() {
  return (
    <section id="tentang" className="bg-ivory py-20 lg:py-28">
      <div className="mx-auto grid max-w-7xl items-center gap-12 px-5 lg:grid-cols-2 lg:gap-20 lg:px-8">
        <FadeUp>
          <div className="relative">
            <img
              src={images.toko}
              alt="Etalase perhiasan emas di Toko Mas Gembira, Pasar Raya Padang"
              className="aspect-[4/3] w-full rounded-2xl object-cover shadow-card" />
            
            <div className="absolute -bottom-6 right-6 rounded-xl bg-crimson px-5 py-4 text-white shadow-lg">
              <p className="font-serif text-3xl font-semibold">27 tahun</p>
              <p className="text-xs text-white/80">melayani warga Padang sejak 1998</p>
            </div>
          </div>
        </FadeUp>

        <FadeUp delay={0.05}>
          <h2 className="font-serif text-3xl font-semibold leading-tight text-charcoal sm:text-4xl">
            Toko Perhiasan Terpercaya di Padang Barat
          </h2>
          <p className="mt-5 leading-relaxed text-charcoal/70">
            Berawal dari kios kecil di Pasar Raya Padang, Toko Mas Gembira telah menjadi pilihan keluarga Minang untuk
            perhiasan pernikahan, hadiah, hingga simpanan masa depan. Kami menjual emas dan perak dengan kadar yang jelas,
            menerima pesanan custom, serta membeli kembali emas Anda dengan harga yang adil.
          </p>
          <ul className="mt-8 space-y-6">
            {aboutHighlights.map(({ icon: Icon, title, text }) =>
            <li key={title} className="flex gap-4">
                <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white text-gold-dark shadow-card">
                  <Icon className="h-5 w-5" strokeWidth={1.75} />
                </span>
                <div>
                  <p className="font-semibold text-charcoal">{title}</p>
                  <p className="mt-0.5 text-sm text-charcoal/60">{text}</p>
                </div>
              </li>
            )}
          </ul>
        </FadeUp>
      </div>
    </section>);

}