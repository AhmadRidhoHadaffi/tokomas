import React from 'react';
import {
  Bar,
  BarChart,
  CartesianGrid,
  Legend,
  Line,
  LineChart,
  ResponsiveContainer,
  Tooltip,
  XAxis,
  YAxis } from
'recharts';
import { formatRupiah, formatRupiahCompact } from '../../utils/format';

const axisStyle = { fontSize: 12, fill: 'rgba(28,28,28,0.5)', fontFamily: 'Poppins' };
const tooltipStyle = {
  borderRadius: 12,
  border: '1px solid #E5E7EB',
  fontFamily: 'Poppins',
  fontSize: 12,
  boxShadow: '0 8px 24px -8px rgba(0,0,0,0.15)'
};

interface SalesLineChartProps {
  data: {day: string;total: number;}[];
}

export function SalesLineChart({ data }: SalesLineChartProps) {
  return (
    <ResponsiveContainer width="100%" height="100%">
      <LineChart data={data} margin={{ top: 10, right: 12, left: 0, bottom: 0 }}>
        <CartesianGrid stroke="#F1ECE2" vertical={false} />
        <XAxis dataKey="day" tick={axisStyle} axisLine={false} tickLine={false} />
        <YAxis tick={axisStyle} axisLine={false} tickLine={false} tickFormatter={(v: number) => formatRupiahCompact(v)} width={72} />
        <Tooltip formatter={(v: number) => [formatRupiah(v), 'Penjualan']} contentStyle={tooltipStyle} cursor={{ stroke: '#D4AF37', strokeOpacity: 0.3 }} />
        <Line type="monotone" dataKey="total" stroke="#D4AF37" strokeWidth={3} dot={{ r: 4, fill: '#fff', stroke: '#B8860B', strokeWidth: 2 }} activeDot={{ r: 6, fill: '#D4AF37' }} />
      </LineChart>
    </ResponsiveContainer>);

}

interface CashflowBarChartProps {
  data: {week: string;pemasukan: number;pengeluaran: number;}[];
}

export function CashflowBarChart({ data }: CashflowBarChartProps) {
  return (
    <ResponsiveContainer width="100%" height="100%">
      <BarChart data={data} margin={{ top: 10, right: 4, left: 0, bottom: 0 }} barGap={4}>
        <CartesianGrid stroke="#F1ECE2" vertical={false} />
        <XAxis dataKey="week" tick={axisStyle} axisLine={false} tickLine={false} tickFormatter={(v: string) => v.replace('Minggu ', 'M')} />
        <YAxis tick={axisStyle} axisLine={false} tickLine={false} tickFormatter={(v: number) => formatRupiahCompact(v)} width={64} />
        <Tooltip formatter={(v: number, name: string) => [formatRupiah(v), name === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran']} contentStyle={tooltipStyle} cursor={{ fill: 'rgba(212,175,55,0.08)' }} />
        <Legend iconType="circle" wrapperStyle={{ fontSize: 12, fontFamily: 'Poppins' }} formatter={(v: string) => v === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran'} />
        <Bar dataKey="pemasukan" fill="#D4AF37" radius={[6, 6, 0, 0]} maxBarSize={22} />
        <Bar dataKey="pengeluaran" fill="#8B0000" radius={[6, 6, 0, 0]} maxBarSize={22} />
      </BarChart>
    </ResponsiveContainer>);

}

interface SimpleBarChartProps {
  data: {label: string;value: number;}[];
  currency?: boolean;
}

export function SimpleBarChart({ data, currency = true }: SimpleBarChartProps) {
  return (
    <ResponsiveContainer width="100%" height="100%">
      <BarChart data={data} margin={{ top: 10, right: 4, left: 0, bottom: 0 }}>
        <CartesianGrid stroke="#F1ECE2" vertical={false} />
        <XAxis dataKey="label" tick={axisStyle} axisLine={false} tickLine={false} />
        <YAxis tick={axisStyle} axisLine={false} tickLine={false} tickFormatter={(v: number) => currency ? formatRupiahCompact(v) : String(v)} width={currency ? 72 : 36} />
        <Tooltip formatter={(v: number) => [currency ? formatRupiah(v) : v, 'Nilai']} contentStyle={tooltipStyle} cursor={{ fill: 'rgba(212,175,55,0.08)' }} />
        <Bar dataKey="value" fill="#D4AF37" radius={[6, 6, 0, 0]} maxBarSize={48} />
      </BarChart>
    </ResponsiveContainer>);

}