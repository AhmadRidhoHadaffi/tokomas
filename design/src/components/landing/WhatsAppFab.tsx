import React from 'react';
import { MessageCircleIcon } from 'lucide-react';
import { store } from '../../data/images';

export function WhatsAppFab() {
  return (
    <a
      href={store.whatsapp}
      target="_blank"
      rel="noreferrer"
      aria-label="Chat dengan kami via WhatsApp"
      className="fixed bottom-5 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-wa text-white shadow-lg shadow-black/25 transition-transform duration-150 ease-out active:scale-95 md:hidden">
      
      <MessageCircleIcon className="h-7 w-7" strokeWidth={2} />
    </a>);

}