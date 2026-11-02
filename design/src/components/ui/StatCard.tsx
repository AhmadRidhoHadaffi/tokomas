import React from "react";
import { TrendingDownIcon, TrendingUpIcon, BoxIcon } from "lucide-react";
interface StatCardProps {
  icon: BoxIcon;
  label: string;
  value: string;
  trend?: {
    value: string;
    up: boolean;
  };
  hint?: string;
  featured?: boolean;
}
export function StatCard({
  icon: Icon,
  label,
  value,
  trend,
  hint,
  featured = false
}: StatCardProps) {
  return <div className={`flex h-full flex-col rounded-2xl p-5 ${featured ? 'bg-charcoal text-white shadow-card' : 'border border-line bg-white shadow-card'}`}>
      <div className="flex items-center justify-between">
        <span className={`flex h-10 w-10 items-center justify-center rounded-xl ${featured ? 'bg-gold/15 text-gold' : 'bg-ivory text-gold-dark'}`}>
          <Icon className="h-5 w-5" strokeWidth={1.75} />
        </span>
        {trend && <span className={`inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium ${trend.up ? featured ? 'bg-emerald-400/15 text-emerald-300' : 'bg-emerald-50 text-emerald-700' : featured ? 'bg-red-400/15 text-red-300' : 'bg-red-50 text-red-700'}`}>
            {trend.up ? <TrendingUpIcon className="h-3.5 w-3.5" /> : <TrendingDownIcon className="h-3.5 w-3.5" />}
            {trend.value}
          </span>}
      </div>
      <p className={`mt-4 text-sm ${featured ? 'text-white/65' : 'text-charcoal/55'}`}>{label}</p>
      <p className={`mt-1 font-serif font-semibold ${featured ? 'text-3xl text-gold' : 'text-2xl text-charcoal'}`}>{value}</p>
      {hint && <p className={`mt-auto pt-2 text-xs ${featured ? 'text-white/50' : 'text-charcoal/45'}`}>{hint}</p>}
    </div>;
}