import React, { useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { BellIcon } from 'lucide-react';
import { Badge } from '../ui/Badge';
import { sentNotifications } from '../../data/notifications';
import { formatDateTime, initials } from '../../utils/format';

interface HeaderActionsProps {
  name: string;
  roleLabel: string;
}

const kindTone = { 'Status Pesanan': 'blue', Pembayaran: 'orange', Promosi: 'gold' } as const;

export function HeaderActions({ name, roleLabel }: HeaderActionsProps) {
  const [open, setOpen] = useState(false);
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!open) return;
    const onClick = (e: MouseEvent) => {
      if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false);
    };
    document.addEventListener('mousedown', onClick);
    return () => document.removeEventListener('mousedown', onClick);
  }, [open]);

  return (
    <div className="flex items-center gap-2 sm:gap-4">
      <div ref={ref} className="relative">
        <button
          type="button"
          onClick={() => setOpen((v) => !v)}
          aria-label="Notifikasi"
          aria-expanded={open}
          className="relative rounded-lg p-2 text-charcoal/65 transition-colors duration-150 hover:bg-ivory hover:text-charcoal">
          
          <BellIcon className="h-5 w-5" strokeWidth={1.75} />
          <span className="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-crimson ring-2 ring-white" />
        </button>
        <AnimatePresence>
          {open &&
          <motion.div
            initial={{ opacity: 0, y: -6, scale: 0.97 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: -6, scale: 0.97 }}
            transition={{ duration: 0.16, ease: [0.23, 1, 0.32, 1] }}
            className="absolute right-0 top-12 z-40 w-80 origin-top-right overflow-hidden rounded-xl border border-line bg-white shadow-xl">
            
              <p className="border-b border-line px-4 py-3 text-sm font-semibold text-charcoal">Aktivitas terbaru</p>
              <ul className="max-h-80 divide-y divide-line overflow-y-auto">
                {sentNotifications.slice(0, 4).map((n) =>
              <li key={n.id} className="px-4 py-3">
                    <Badge tone={kindTone[n.kind]}>{n.kind}</Badge>
                    <p className="mt-1.5 text-sm text-charcoal/80">{n.content}</p>
                    <p className="mt-1 text-xs text-charcoal/45">
                      {n.recipient} · {formatDateTime(n.time)}
                    </p>
                  </li>
              )}
              </ul>
            </motion.div>
          }
        </AnimatePresence>
      </div>

      <div className="flex items-center gap-3">
        <span className="flex h-9 w-9 items-center justify-center rounded-full bg-gold-gradient text-xs font-semibold text-charcoal">
          {initials(name)}
        </span>
        <div className="hidden leading-tight sm:block">
          <p className="text-sm font-semibold text-charcoal">{name}</p>
          <p className="text-xs text-charcoal/50">{roleLabel}</p>
        </div>
      </div>
    </div>);

}