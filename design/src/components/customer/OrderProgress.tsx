import React from 'react';
import { CheckIcon } from 'lucide-react';
import type { CustomStatus } from '../../types/customOrder';
import { customStatusFlow } from '../../data/customOrders';

const labels: Record<CustomStatus, string> = {
  Masuk: 'Pesanan Masuk',
  Diproses: 'Diproses',
  'Dalam Pembuatan': 'Dalam Pembuatan',
  Selesai: 'Selesai',
  Diambil: 'Diambil'
};

export function OrderProgress({ status }: {status: CustomStatus;}) {
  const current = customStatusFlow.indexOf(status);
  return (
    <div>
      <ol className="flex items-start" aria-label="Progres pesanan">
        {customStatusFlow.map((step, i) => {
          const done = i < current;
          const active = i === current;
          return (
            <li key={step} className="relative flex flex-1 flex-col items-center" aria-current={active ? 'step' : undefined}>
              {i > 0 &&
              <span className={`absolute right-1/2 top-3.5 h-0.5 w-full ${i <= current ? 'bg-gold' : 'bg-line'}`} aria-hidden="true" />
              }
              <span
                className={`relative z-10 flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-semibold ${
                done ? 'bg-gold text-charcoal' : active ? 'bg-charcoal text-gold ring-4 ring-gold/30' : 'border border-line bg-white text-charcoal/35'}`
                }>
                
                {done ? <CheckIcon className="h-3.5 w-3.5" /> : i + 1}
              </span>
              <span className={`mt-2 hidden px-1 text-center text-xs sm:block ${active ? 'font-semibold text-charcoal' : done ? 'text-charcoal/70' : 'text-charcoal/40'}`}>
                {labels[step]}
              </span>
            </li>);

        })}
      </ol>
      <p className="mt-3 text-center text-xs text-charcoal/60 sm:hidden">
        Tahap {current + 1} dari {customStatusFlow.length} · <span className="font-semibold text-charcoal">{labels[status]}</span>
      </p>
    </div>);

}