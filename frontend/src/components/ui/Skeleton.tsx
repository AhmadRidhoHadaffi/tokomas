export function Skeleton({ className = '' }: { className?: string }) {
  const classes = ['animate-pulse bg-charcoal/10 rounded', className].filter(Boolean).join(' ');
  return <div className={classes} />;
}
