import React from 'react';

export const inputClass =
'w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-sm text-charcoal placeholder:text-charcoal/40 outline-none transition-[border-color,box-shadow] duration-150 focus:border-gold focus:ring-4 focus:ring-gold/20 disabled:bg-ivory disabled:text-charcoal/50';

interface FieldProps {
  label: string;
  htmlFor: string;
  hint?: string;
  error?: string;
  className?: string;
  children: React.ReactNode;
}

export function Field({ label, htmlFor, hint, error, className = '', children }: FieldProps) {
  return (
    <div className={className}>
      <label htmlFor={htmlFor} className="mb-1.5 block text-sm font-medium text-charcoal/80">
        {label}
      </label>
      {children}
      {error ?
      <p className="mt-1.5 text-xs text-crimson">{error}</p> :
      hint ?
      <p className="mt-1.5 text-xs text-charcoal/50">{hint}</p> :
      null}
    </div>);

}

export function TextInput(props: React.InputHTMLAttributes<HTMLInputElement>) {
  const { className = '', ...rest } = props;
  return <input className={`${inputClass} ${className}`} {...rest} />;
}

export function SelectInput(props: React.SelectHTMLAttributes<HTMLSelectElement>) {
  const { className = '', children, ...rest } = props;
  return (
    <select className={`${inputClass} appearance-none bg-[length:16px] bg-[right_12px_center] bg-no-repeat pr-9 ${className}`} style={{ backgroundImage: "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239CA3AF' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E\")" }} {...rest}>
      {children}
    </select>);

}

export function TextArea(props: React.TextareaHTMLAttributes<HTMLTextAreaElement>) {
  const { className = '', ...rest } = props;
  return <textarea className={`${inputClass} min-h-[96px] resize-y ${className}`} {...rest} />;
}