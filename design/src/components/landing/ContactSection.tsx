import React from 'react';
import { ClockIcon, MailIcon, MapPinIcon, MessageCircleIcon, PhoneIcon } from 'lucide-react';
import { FadeUp } from './FadeUp';
import { buttonClass } from '../ui/Button';
import { store } from '../../data/images';

export function ContactSection() {
  return (
    <section id="kontak" className="bg-charcoal py-20 lg:py-28">
      <div className="mx-auto grid max-w-7xl gap-12 px-5 lg:grid-cols-5 lg:px-8">
        <FadeUp className="lg:col-span-2">
          <h2 className="font-serif text-3xl font-semibold text-white sm:text-4xl">Kunjungi Toko Kami</h2>
          <p className="mt-4 text-white/60">Kami tunggu kedatangan Anda di Pasar Raya Padang.</p>

          <ul className="mt-8 space-y-5 text-sm">
            <li className="flex gap-4">
              <MapPinIcon className="mt-0.5 h-5 w-5 shrink-0 text-gold" strokeWidth={1.75} />
              <span className="text-white/80">{store.address}</span>
            </li>
            <li className="flex gap-4">
              <PhoneIcon className="mt-0.5 h-5 w-5 shrink-0 text-gold" strokeWidth={1.75} />
              <span className="text-white/80">{store.phone}</span>
            </li>
            <li className="flex gap-4">
              <MailIcon className="mt-0.5 h-5 w-5 shrink-0 text-gold" strokeWidth={1.75} />
              <span className="text-white/80">{store.email}</span>
            </li>
            <li className="flex gap-4">
              <ClockIcon className="mt-0.5 h-5 w-5 shrink-0 text-gold" strokeWidth={1.75} />
              <div className="space-y-1">
                {store.hours.map((h) =>
                <p key={h.day} className="text-white/80">
                    <span className="inline-block w-32 text-white/50">{h.day}</span>
                    {h.time}
                  </p>
                )}
              </div>
            </li>
          </ul>

          <a href={store.whatsapp} target="_blank" rel="noreferrer" className={buttonClass('primary', 'md', 'mt-8 !bg-wa !bg-none !text-white')}>
            <MessageCircleIcon className="h-4 w-4" />
            Chat WhatsApp
          </a>
        </FadeUp>

        <FadeUp delay={0.05} className="lg:col-span-3">
          <div className="overflow-hidden rounded-2xl border border-gold/30">
            <iframe
              title="Peta lokasi Toko Mas Gembira di Pasar Raya Padang"
              src="https://www.openstreetmap.org/export/embed.html?bbox=100.3560%2C-0.9560%2C100.3680%2C-0.9440&layer=mapnik&marker=-0.9500%2C100.3620"
              className="h-[360px] w-full lg:h-[440px]"
              loading="lazy" />
            
          </div>
        </FadeUp>
      </div>
    </section>);

}