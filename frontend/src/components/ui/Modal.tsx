import React from 'react';

interface ModalProps {
  isOpen: boolean;
  onClose: () => void;
  title: string;
  children: React.ReactNode;
}

export function Modal({ isOpen, onClose, title, children }: ModalProps) {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-charcoal/50" onClick={onClose}>
      <div className="bg-ivory rounded-lg shadow-card max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" onClick={(e) => e.stopPropagation()}>
        <div className="flex items-center justify-between px-6 py-4 border-b border-line">
          <h2 className="text-xl font-semibold text-charcoal">{title}</h2>
          <button onClick={onClose} className="text-charcoal/60 hover:text-charcoal">✕</button>
        </div>
        <div className="p-6">{children}</div>
      </div>
    </div>
  );
}
