import React from 'react';
import { Link } from 'react-router-dom';
import { FadeUp } from './FadeUp';
import { buttonClass } from '../ui/Button';
import { customSteps } from '../../data/landing';

export function CustomOrderSection() {
  return (
    <section id="custom" className="bg-ivory py-20 lg:py-28">
      <div className="mx-auto grid max-w-7xl gap-14 px-5 lg:grid-cols-2 lg:gap-20 lg:px-8">
        <FadeUp className="lg:sticky lg:top-28 lg:self-start">
          <h2 className="font-serif text-3xl font-semibold leading-tight text-charcoal sm:text-4xl">
            Wujudkan Perhiasan Impian Anda
          </h2>
          <p className="mt-5 max-w-lg leading-relaxed text-charcoal/70">
            Cincin nikah dengan ukiran nama, liontin inisial, atau gelang bermotif Rumah Gadang — pengrajin kami siap
            membuatnya sesuai keinginan. Ajukan pesanan dari akun Anda, bayar DP, dan pantau prosesnya hingga selesai.
          </p>
          <Link to="/daftar" className={buttonClass('primary', 'lg', 'mt-8')}>
            Pesan Custom Sekarang
          </Link>
        </FadeUp>

        <ol className="relative">
          {customSteps.map(({ icon: Icon, title, text }, i) =>
          <FadeUp key={title} delay={i * 0.05}>
              <li className="relative flex gap-5 pb-10 last:pb-0">
                {i < customSteps.length - 1 &&
              <span className="absolute left-[23px] top-12 h-[calc(100%-48px)] w-px bg-gold/50" aria-hidden="true" />
              }
                <span className="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-full border-2 border-gold bg-white text-gold-dark">
                  <Icon className="h-5 w-5" strokeWidth={1.75} />
                  <span className="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-crimson text-[10px] font-semibold text-white">
                    {i + 1}
                  </span>
                </span>
                <div className="pt-1.5">
                  <p className="font-semibold text-charcoal">{title}</p>
                  <p className="mt-1 text-sm leading-relaxed text-charcoal/60">{text}</p>
                </div>
              </li>
            </FadeUp>
          )}
        </ol>
      </div>
    </section>);

}