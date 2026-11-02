import React from 'react';
import { CheckIcon } from 'lucide-react';

const steps = ['Pelanggan', 'Produk', 'Pembayaran', 'Konfirmasi'];

export function TransactionStepper({ current }: {current: number;}) {
  return (
    <ol className="flex items-center" aria-label="Langkah transaksi">
      {steps.map((label, i) => {
        const index = i + 1;
        const done = index < current;
        const active = index === current;
        return (
          <li key={label} className={`flex items-center ${i < steps.length - 1 ? 'flex-1' : ''}`} aria-current={active ? 'step' : undefined}>
            <span className="flex items-center gap-2.5">
              <span
                className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-semibold transition-colors duration-200 ${
                done ? 'bg-gold text-charcoal' : active ? 'bg-charcoal text-gold' : 'border border-line bg-white text-charcoal/40'}`
                }>
                
                {done ? <CheckIcon className="h-4 w-4" /> : index}
              </span>
              <span className={`hidden whitespace-nowrap text-sm font-medium sm:inline ${active ? 'text-charcoal' : done ? 'text-charcoal/70' : 'text-charcoal/40'}`}>
                {label}
              </span>
            </span>
            {i < steps.length - 1 && <span className={`mx-3 h-px flex-1 ${done ? 'bg-gold' : 'bg-line'}`} aria-hidden="true" />}
          </li>);

      })}
    </ol>);

}