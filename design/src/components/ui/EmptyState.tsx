import React from 'react';

interface EmptyStateProps {
  title: string;
  description?: string;
  action?: React.ReactNode;
}

export function EmptyState({ title, description, action }: EmptyStateProps) {
  return (
    <div className="flex flex-col items-center justify-center px-6 py-14 text-center">
      <svg viewBox="0 0 120 96" className="h-24 w-32" aria-hidden="true">
        <ellipse cx="60" cy="86" rx="38" ry="5" fill="#F6EDDD" />
        <circle cx="60" cy="52" r="26" fill="none" stroke="#D4AF37" strokeWidth="3" />
        <circle cx="60" cy="52" r="20" fill="none" stroke="#D4AF37" strokeWidth="1" opacity="0.5" />
        <path d="M48 26 L54 16 H66 L72 26 L60 36 Z" fill="#FDF8F0" stroke="#B8860B" strokeWidth="1.6" strokeLinejoin="round" />
        <path d="M48 26 H72 M54 16 L60 36 L66 16" fill="none" stroke="#B8860B" strokeWidth="1" opacity="0.7" />
        <circle cx="96" cy="22" r="2" fill="#D4AF37" />
        <circle cx="22" cy="34" r="1.5" fill="#D4AF37" />
      </svg>
      <p className="mt-4 font-serif text-lg font-semibold text-charcoal">{title}</p>
      {description && <p className="mt-1 max-w-sm text-sm text-charcoal/55">{description}</p>}
      {action && <div className="mt-5">{action}</div>}
    </div>);

}