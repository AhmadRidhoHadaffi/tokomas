import React from 'react';

type ButtonVariant = 'primary' | 'outline' | 'outlineDark' | 'outlineLight' | 'ghost' | 'danger' | 'dark' | 'light';
type ButtonSize = 'sm' | 'md' | 'lg';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: ButtonVariant;
  size?: ButtonSize;
  children: React.ReactNode;
}

const variantClasses: Record<ButtonVariant, string> = {
  primary: 'bg-gold hover:bg-gold-dark text-charcoal font-semibold',
  outline: 'border-2 border-gold text-gold hover:bg-gold hover:text-charcoal',
  outlineDark: 'border-2 border-charcoal text-charcoal hover:bg-charcoal hover:text-ivory',
  outlineLight: 'border-2 border-ivory text-ivory hover:bg-ivory hover:text-charcoal',
  ghost: 'text-charcoal hover:bg-charcoal/5',
  danger: 'bg-crimson hover:bg-crimson-dark text-ivory',
  dark: 'bg-charcoal hover:bg-charcoal-soft text-ivory',
  light: 'bg-ivory hover:bg-ivory-dark text-charcoal',
};

const sizeClasses: Record<ButtonSize, string> = {
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-4 py-2 text-base',
  lg: 'px-6 py-3 text-lg',
};

export function Button({ variant = 'primary', size = 'md', className = '', children, ...props }: ButtonProps) {
  const classes = [
    'rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed',
    variantClasses[variant],
    sizeClasses[size],
    className
  ].filter(Boolean).join(' ');

  return (
    <button className={classes} {...props}>
      {children}
    </button>
  );
}
