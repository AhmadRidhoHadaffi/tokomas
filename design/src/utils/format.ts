import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const rupiah = new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  maximumFractionDigits: 0
});

export function formatRupiah(value: number): string {
  return rupiah.format(value).replace(/\u00a0/g, ' ');
}

export function formatRupiahCompact(value: number): string {
  if (value >= 1_000_000_000) return `Rp ${(value / 1_000_000_000).toFixed(1).replace('.', ',')} M`;
  if (value >= 1_000_000) return `Rp ${(value / 1_000_000).toFixed(1).replace('.', ',')} jt`;
  if (value >= 1_000) return `Rp ${Math.round(value / 1_000)} rb`;
  return `Rp ${value}`;
}

export function formatDate(iso: string): string {
  return format(parseISO(iso), 'd MMM yyyy', { locale: id });
}

export function formatDateTime(iso: string): string {
  return format(parseISO(iso), 'd MMM yyyy, HH:mm', { locale: id });
}

export function formatRelativeDay(iso: string): string {
  return format(parseISO(iso), 'EEEE, d MMM', { locale: id });
}

export function transactionTotal(items: {qty: number;price: number;}[], discount = 0): number {
  return items.reduce((sum, item) => sum + item.qty * item.price, 0) - discount;
}

export function initials(name: string): string {
  return name.
  split(' ').
  filter(Boolean).
  slice(0, 2).
  map((part) => part[0]?.toUpperCase()).
  join('');
}