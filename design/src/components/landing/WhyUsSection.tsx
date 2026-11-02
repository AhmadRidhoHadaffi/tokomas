import React from 'react';
import { FadeUp } from './FadeUp';
import { whyUs } from '../../data/landing';

export function WhyUsSection() {
  return (
    <section className="bg-ivory py-20 lg:py-28">
      <div className="mx-auto max-w-7xl px-5 lg:px-8">
        <FadeUp className="mx-auto max-w-2xl text-center">
          <h2 className="font-serif text-3xl font-semibold text-charcoal sm:text-4xl">Mengapa Memilih Toko Mas Gembira</h2>
          <p className="mt-4 text-charcoal/65">Empat alasan pelanggan kembali kepada kami, dari generasi ke generasi.</p>
        </FadeUp>
        <FadeUp className="mt-12">
          <div className="grid overflow-hidden rounded-2xl border border-line bg-white shadow-card sm:grid-cols-2 lg:grid-cols-4">
            {whyUs.map(({ icon: Icon, title, text }, i) =>
            <div
              key={title}
              className={`p-7 ${i > 0 ? 'border-t border-line sm:border-t-0' : ''} ${
              i % 2 === 1 ? 'sm:border-l' : ''} ${
              i >= 2 ? 'sm:border-t lg:border-t-0' : ''} ${i === 2 ? 'lg:border-l' : ''} border-line`}>
              
                <span className="flex h-12 w-12 items-center justify-center rounded-full bg-ivory text-gold-dark">
                  <Icon className="h-6 w-6" strokeWidth={1.5} />
                </span>
                <h3 className="mt-5 font-serif text-lg font-semibold text-charcoal">{title}</h3>
                <p className="mt-2 text-sm leading-relaxed text-charcoal/60">{text}</p>
              </div>
            )}
          </div>
        </FadeUp>
      </div>
    </section>);

}