import type { InputHTMLAttributes, ReactNode, SelectHTMLAttributes, TextareaHTMLAttributes } from 'react';

export const inputClass = 'w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-sm text-charcoal outline-none focus:border-gold focus:ring-4 focus:ring-gold/20';
interface FormFieldProps { label: string; htmlFor: string; hint?: string; error?: string; children: ReactNode; }
export function FormField({ label, htmlFor, hint, error, children }: FormFieldProps) { return <div><label htmlFor={htmlFor} className="mb-1.5 block text-sm font-medium text-charcoal/80">{label}</label>{children}{error ? <p className="mt-1.5 text-xs text-crimson">{error}</p> : hint ? <p className="mt-1.5 text-xs text-charcoal/50">{hint}</p> : null}</div>; }
export function TextInput({ className = '', ...props }: InputHTMLAttributes<HTMLInputElement>) { return <input className={`${inputClass} ${className}`} {...props} />; }
export function SelectInput({ className = '', children, ...props }: SelectHTMLAttributes<HTMLSelectElement>) { return <select className={`${inputClass} ${className}`} {...props}>{children}</select>; }
export function TextArea({ className = '', ...props }: TextareaHTMLAttributes<HTMLTextAreaElement>) { return <textarea className={`${inputClass} ${className}`} {...props} />; }
