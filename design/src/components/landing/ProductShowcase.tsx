import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { FadeUp } from './FadeUp';
import { Modal } from '../ui/Modal';
import { buttonClass } from '../ui/Button';
import { showcaseProducts } from '../../data/products';
import { store } from '../../data/images';
import { formatRupiah } from '../../utils/format';

type Showcase = (typeof showcaseProducts)[number];

export function ProductShowcase() {
  const [selected, setSelected] = useState<Showcase | null>(null);

  return (
    <section id="produk" className="bg-charcoal py-20 lg:py-28">
      <div className="mx-auto max-w-7xl px-5 lg:px-8">
        <FadeUp className="flex flex-col justify-between gap-4 md:flex-row md:items-end">
          <div>
            <h2 className="font-serif text-3xl font-semibold text-white sm:text-4xl">Koleksi Pilihan Kami</h2>
            <p className="mt-3 max-w-lg text-white/60">
              Berat, kadar, dan ukuran tertera jelas. Semua koleksi tersedia di toko dan bisa dipesan lewat akun Anda.
            </p>
          </div>
          <Link to="/daftar" className="text-sm font-medium text-gold hover:underline">
            Lihat semua koleksi →
          </Link>
        </FadeUp>

        <div className="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {showcaseProducts.map((product, i) =>
          <FadeUp key={product.name} delay={i * 0.04} className="h-full">
              <article className="group flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-charcoal-soft transition-[box-shadow,border-color,transform] duration-200 ease-out hover:-translate-y-1 hover:border-gold/50 hover:shadow-gold-glow">
                <div className="relative overflow-hidden">
                  <img src={product.image} alt={product.name} className="aspect-[4/3] w-full object-cover" />
                  <span
                  className={`absolute left-4 top-4 rounded-full px-3 py-1 text-xs font-semibold ${
                  product.metal === 'Emas' ? 'bg-gold text-charcoal' : 'bg-gray-200 text-charcoal'}`
                  }>
                  
                    {product.metal}
                  </span>
                </div>
                <div className="flex flex-1 flex-col p-5">
                  <h3 className="font-serif text-xl font-semibold text-white">{product.name}</h3>
                  <p className="mt-2 flex flex-wrap items-center gap-x-2 text-sm text-white/55">
                    {product.detail.map((d, idx) =>
                  <React.Fragment key={d}>
                        {idx > 0 && <span aria-hidden="true">·</span>}
                        <span>{d}</span>
                      </React.Fragment>
                  )}
                  </p>
                  <p className="mt-4 text-sm text-white/50">
                    Mulai <span className="font-semibold text-gold">{formatRupiah(product.price)}</span>
                  </p>
                  <button
                  type="button"
                  onClick={() => setSelected(product)}
                  className={buttonClass('outlineDark', 'md', 'mt-5 w-full')}>
                  
                    Lihat Detail
                  </button>
                </div>
              </article>
            </FadeUp>
          )}
        </div>
      </div>

      <Modal open={selected !== null} onClose={() => setSelected(null)} title={selected?.name ?? ''} size="lg">
        {selected &&
        <div className="grid gap-6 sm:grid-cols-2">
            <img src={selected.image} alt={selected.name} className="aspect-square w-full rounded-xl object-cover" />
            <div className="flex flex-col">
              <span className="w-fit rounded-full bg-gold/15 px-3 py-1 text-xs font-semibold text-gold-deep">{selected.metal}</span>
              <p className="mt-4 text-sm leading-relaxed text-charcoal/70">{selected.description}</p>
              <dl className="mt-5 divide-y divide-line rounded-xl border border-line">
                {['Berat', 'Kadar', 'Ukuran'].map((label, idx) =>
              <div key={label} className="flex justify-between px-4 py-2.5 text-sm">
                    <dt className="text-charcoal/55">{label}</dt>
                    <dd className="font-medium text-charcoal">{selected.detail[idx]}</dd>
                  </div>
              )}
              </dl>
              <p className="mt-5 text-sm text-charcoal/55">Harga mulai</p>
              <p className="font-serif text-2xl font-semibold text-charcoal">{formatRupiah(selected.price)}</p>
              <div className="mt-auto flex flex-col gap-2 pt-6">
                <a href={store.whatsapp} target="_blank" rel="noreferrer" className={buttonClass('primary', 'md')}>
                  Tanya via WhatsApp
                </a>
                <Link to="/masuk" className={buttonClass('light', 'md')}>
                  Masuk untuk memesan
                </Link>
              </div>
            </div>
          </div>
        }
      </Modal>
    </section>);

}