import React, { useEffect, useId } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { XIcon } from 'lucide-react';

interface ModalProps {
  open: boolean;
  onClose: () => void;
  title: string;
  description?: string;
  size?: 'sm' | 'md' | 'lg' | 'xl';
  footer?: React.ReactNode;
  children: React.ReactNode;
}

const sizeClass = {
  sm: 'max-w-md',
  md: 'max-w-lg',
  lg: 'max-w-2xl',
  xl: 'max-w-4xl'
};

const ease = [0.23, 1, 0.32, 1] as const;

export function Modal({ open, onClose, title, description, size = 'md', footer, children }: ModalProps) {
  const titleId = useId();

  useEffect(() => {
    if (!open) return;
    const onKey = (event: KeyboardEvent) => {
      if (event.key === 'Escape') onClose();
    };
    const previous = document.body.style.overflow;
    document.body.style.overflow = 'hidden';
    window.addEventListener('keydown', onKey);
    return () => {
      document.body.style.overflow = previous;
      window.removeEventListener('keydown', onKey);
    };
  }, [open, onClose]);

  return (
    <AnimatePresence>
      {open &&
      <div className="fixed inset-0 z-[60] flex items-end justify-center p-0 sm:items-center sm:p-6">
          <motion.div
          className="absolute inset-0 bg-charcoal/60 backdrop-blur-[2px]"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          transition={{ duration: 0.2, ease }}
          onClick={onClose} />
        
          <motion.div
          role="dialog"
          aria-modal="true"
          aria-labelledby={titleId}
          className={`relative flex max-h-[92vh] w-full ${sizeClass[size]} flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:rounded-2xl`}
          initial={{ opacity: 0, scale: 0.96, y: 12 }}
          animate={{ opacity: 1, scale: 1, y: 0 }}
          exit={{ opacity: 0, scale: 0.96, y: 12 }}
          transition={{ duration: 0.22, ease }}>
          
            <div className="flex items-start justify-between gap-4 border-b border-line px-6 py-5">
              <div>
                <h2 id={titleId} className="font-serif text-xl font-semibold text-charcoal">
                  {title}
                </h2>
                {description && <p className="mt-1 text-sm text-charcoal/60">{description}</p>}
              </div>
              <button
              type="button"
              onClick={onClose}
              aria-label="Tutup"
              className="rounded-lg p-2 text-charcoal/50 transition-colors duration-150 hover:bg-ivory hover:text-charcoal">
              
                <XIcon className="h-5 w-5" />
              </button>
            </div>
            <div className="scrollbar-thin flex-1 overflow-y-auto px-6 py-5">{children}</div>
            {footer &&
          <div className="flex flex-col-reverse gap-2 border-t border-line bg-ivory/60 px-6 py-4 sm:flex-row sm:justify-end">
                {footer}
              </div>
          }
          </motion.div>
        </div>
      }
    </AnimatePresence>);

}