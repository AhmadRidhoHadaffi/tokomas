import React from 'react';

export type ButtonVariant = 'primary' | 'outline' | 'outlineDark' | 'outlineLight' | 'ghost' | 'danger' | 'dark' | 'light';
export type ButtonSize = 'sm' | 'md' | 'lg';

const variantClass: Record<ButtonVariant, string> = {
  primary: 'bg-gold-gradient text-charcoal shadow-sm hover:brightness-110 hover:shadow-gold-glow',
  outline: 'border border-gold text-gold-deep bg-transparent hover:bg-gold/10',
  outlineDark: 'border border-gold/80 text-gold bg-transparent hover:bg-gold/10 hover:border-gold',
  outlineLight: 'border border-white/70 text-white bg-transparent hover:bg-white/10',
  ghost: 'text-charcoal/70 hover:bg-ivory-dark hover:text-charcoal',
  danger: 'bg-crimson text-white hover:bg-crimson-dark',
  dark: 'bg-charcoal text-white hover:bg-charcoal-soft',
  light: 'bg-white text-charcoal border border-line hover:border-gold/60 hover:bg-ivory'
};

const sizeClass: Record<ButtonSize, string> = {
  sm: 'h-9 px-3.5 text-xs gap-1.5',
  md: 'h-11 px-5 text-sm gap-2',
  lg: 'h-12 px-6 text-[15px] gap-2'
};

export function buttonClass(variant: ButtonVariant = 'primary', size: ButtonSize = 'md', extra = ''): string {
  return [
  'inline-flex items-center justify-center whitespace-nowrap rounded-lg font-medium',
  'transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out',
  'active:scale-[0.98] disabled:pointer-events-none disabled:opacity-50',
  'focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30',
  variantClass[variant],
  sizeClass[size],
  extra].
  join(' ');
}

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: ButtonVariant;
  size?: ButtonSize;
}

export function Button({ variant = 'primary', size = 'md', className = '', type = 'button', ...rest }: ButtonProps) {
  return <button type={type} className={buttonClass(variant, size, className)} {...rest} />;
}