import React from 'react';
import { motion } from 'framer-motion';

export interface TabItem<T extends string> {
  value: T;
  label: string;
  count?: number;
}

interface TabsProps<T extends string> {
  items: TabItem<T>[];
  value: T;
  onChange: (value: T) => void;
  layoutId: string;
  variant?: 'underline' | 'pill';
}

const ease = [0.23, 1, 0.32, 1] as const;

export function Tabs<T extends string>({ items, value, onChange, layoutId, variant = 'underline' }: TabsProps<T>) {
  if (variant === 'pill') {
    return (
      <div role="tablist" className="scrollbar-thin flex gap-1.5 overflow-x-auto pb-1">
        {items.map((item) => {
          const active = item.value === value;
          return (
            <button
              key={item.value}
              role="tab"
              type="button"
              aria-selected={active}
              onClick={() => onChange(item.value)}
              className={`relative whitespace-nowrap rounded-full px-4 py-2 text-sm font-medium transition-colors duration-150 ${
              active ? 'text-charcoal' : 'text-charcoal/60 hover:bg-white hover:text-charcoal'}`
              }>
              
              {active &&
              <motion.span
                layoutId={layoutId}
                className="absolute inset-0 rounded-full bg-gold-gradient"
                transition={{ duration: 0.22, ease }} />

              }
              <span className="relative flex items-center gap-2">
                {item.label}
                {item.count !== undefined &&
                <span className={`rounded-full px-1.5 text-[11px] ${active ? 'bg-charcoal/15' : 'bg-charcoal/5'}`}>
                    {item.count}
                  </span>
                }
              </span>
            </button>);

        })}
      </div>);

  }

  return (
    <div role="tablist" className="scrollbar-thin flex gap-1 overflow-x-auto border-b border-line">
      {items.map((item) => {
        const active = item.value === value;
        return (
          <button
            key={item.value}
            role="tab"
            type="button"
            aria-selected={active}
            onClick={() => onChange(item.value)}
            className={`relative whitespace-nowrap px-4 py-3 text-sm font-medium transition-colors duration-150 ${
            active ? 'text-charcoal' : 'text-charcoal/55 hover:text-charcoal'}`
            }>
            
            <span className="flex items-center gap-2">
              {item.label}
              {item.count !== undefined &&
              <span className={`rounded-full px-2 py-0.5 text-[11px] ${active ? 'bg-gold/20 text-gold-deep' : 'bg-charcoal/5'}`}>
                  {item.count}
                </span>
              }
            </span>
            {active &&
            <motion.span
              layoutId={layoutId}
              className="absolute inset-x-2 -bottom-px h-0.5 rounded-full bg-gold"
              transition={{ duration: 0.22, ease }} />

            }
          </button>);

      })}
    </div>);

}