import { SearchIcon } from 'lucide-react';
interface SearchInputProps { value: string; onChange: (value: string) => void; placeholder?: string; }
export function SearchInput({ value, onChange, placeholder = 'Cari...' }: SearchInputProps) { return <label className="relative block"><SearchIcon className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-charcoal/40" /><input value={value} onChange={(event) => onChange(event.target.value)} placeholder={placeholder} className="w-full rounded-lg border border-line bg-white py-2 pl-9 pr-3 text-sm outline-none focus:border-gold" /></label>; }
