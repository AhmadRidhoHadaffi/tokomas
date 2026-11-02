import React from 'react';

export function Skeleton({ className = 'h-4 w-full' }: {className?: string;}) {
  return <div className={`animate-pulse rounded-md bg-gold/15 ${className}`} aria-hidden="true" />;
}