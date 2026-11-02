import React from 'react';

interface EmptyStateProps {
  icon?: React.ReactNode;
  title: string;
  description?: string;
}

export function EmptyState({ icon, title, description }: EmptyStateProps) {
  return (
    <div className="flex flex-col items-center justify-center py-12 text-center">
      {icon && <div className="mb-4 text-charcoal/20">{icon}</div>}
      <h3 className="text-lg font-medium text-charcoal mb-1">{title}</h3>
      {description && <p className="text-charcoal/60 text-sm">{description}</p>}
    </div>
  );
}
