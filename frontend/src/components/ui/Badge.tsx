import React from 'react';

type BadgeTone = 'blue' | 'yellow' | 'orange' | 'green' | 'grey' | 'red' | 'gold' | 'crimson';

interface BadgeProps {
  tone: BadgeTone;
  children: React.ReactNode;
}

const toneClasses: Record<BadgeTone, string> = {
  blue: 'bg-blue-100 text-blue-700',
  yellow: 'bg-yellow-100 text-yellow-700',
  orange: 'bg-orange-100 text-orange-700',
  green: 'bg-green-100 text-green-700',
  grey: 'bg-gray-100 text-gray-700',
  red: 'bg-red-100 text-red-700',
  gold: 'bg-gold-light text-gold-deep',
  crimson: 'bg-crimson/10 text-crimson',
};

export function Badge({ tone, children }: BadgeProps) {
  const classes = [
    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
    toneClasses[tone]
  ].join(' ');

  return <span className={classes}>{children}</span>;
}
