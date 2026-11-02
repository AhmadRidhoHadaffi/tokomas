import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { ArrowLeftIcon } from 'lucide-react';
import { LogoMark } from '../brand/Logo';

interface AuthShellProps {
  title: string;
  subtitle: string;
  children: React.ReactNode;
}

export function AuthShell({ title, subtitle, children }: AuthShellProps) {
  return (
    <div
      className="relative flex min-h-screen w-full items-center justify-center px-4 py-12"
      style={{ background: 'linear-gradient(160deg, #1C1C1C 0%, #1C1C1C 50%, #4A3812 100%)' }}>
      
      <div className="bg-arabesque absolute inset-0" aria-hidden="true" />
      <Link
        to="/"
        className="absolute left-5 top-5 inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-white/70 transition-colors duration-150 hover:bg-white/10 hover:text-white">
        
        <ArrowLeftIcon className="h-4 w-4" />
        Beranda
      </Link>
      <motion.main
        initial={{ opacity: 0, y: 12, scale: 0.98 }}
        animate={{ opacity: 1, y: 0, scale: 1 }}
        transition={{ duration: 0.25, ease: [0.23, 1, 0.32, 1] }}
        className="relative w-full max-w-md rounded-2xl bg-white p-7 shadow-2xl shadow-black/40 sm:p-9">
        
        <div className="flex flex-col items-center text-center">
          <LogoMark className="h-16 w-16" />
          <p className="mt-3 font-serif text-lg font-semibold text-charcoal">Toko Mas Gembira</p>
          <div className="mx-auto mt-5 h-px w-12 bg-gold" aria-hidden="true" />
          <h1 className="mt-5 font-serif text-2xl font-semibold text-charcoal">{title}</h1>
          <p className="mt-1.5 text-sm text-charcoal/60">{subtitle}</p>
        </div>
        <div className="mt-7">{children}</div>
      </motion.main>
    </div>);

}