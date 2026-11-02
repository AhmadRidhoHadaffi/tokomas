import type { LucideIcon } from 'lucide-react';
interface StatCardProps { icon: LucideIcon; label: string; value: string; hint?: string; }
export function StatCard({ icon: Icon, label, value, hint }: StatCardProps) { return <div className="rounded-2xl border border-line bg-white p-5 shadow-card"><Icon className="h-5 w-5 text-gold-dark" /><p className="mt-4 text-sm text-charcoal/55">{label}</p><p className="mt-1 font-serif text-2xl font-semibold text-charcoal">{value}</p>{hint && <p className="mt-2 text-xs text-charcoal/45">{hint}</p>}</div>; }
