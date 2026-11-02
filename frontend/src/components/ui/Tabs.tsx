import { motion } from 'framer-motion';

export interface TabItem<T extends string> { value: T; label: string; count?: number; }
interface TabsProps<T extends string> { items: TabItem<T>[]; value: T; onChange: (value: T) => void; layoutId: string; }

export function Tabs<T extends string>({ items, value, onChange, layoutId }: TabsProps<T>) {
  return <div role="tablist" className="flex gap-1 overflow-x-auto border-b border-line">{items.map((item) => {
    const active = item.value === value;
    return <button key={item.value} type="button" role="tab" aria-selected={active} onClick={() => onChange(item.value)} className={`relative whitespace-nowrap px-4 py-3 text-sm font-medium ${active ? 'text-charcoal' : 'text-charcoal/55'}`}>
      {item.label}{item.count !== undefined && <span className="ml-2 rounded-full bg-charcoal/5 px-2 py-0.5 text-xs">{item.count}</span>}
      {active && <motion.span layoutId={layoutId} className="absolute inset-x-2 bottom-0 h-0.5 bg-gold" />}
    </button>;
  })}</div>;
}
