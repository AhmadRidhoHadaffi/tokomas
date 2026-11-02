import React from 'react';

interface LogoMarkProps {
  className?: string;
}

export function LogoMark({ className = 'h-10 w-10' }: LogoMarkProps) {
  return (
    <svg viewBox="0 0 100 100" className={className} role="img" aria-label="Logo Toko Mas Gembira">
      <circle cx="50" cy="50" r="49" fill="#FDF8F0" />
      <circle cx="50" cy="50" r="47" fill="none" stroke="#D4AF37" strokeWidth="1.2" opacity="0.6" />
      <path d="M30 32 L26 15 L38 23 L44 10 L50 21 L56 10 L62 23 L74 15 L70 32 Z" fill="#8B0000" />
      <circle cx="26" cy="14" r="2.4" fill="#8B0000" />
      <circle cx="44" cy="9" r="2.4" fill="#8B0000" />
      <circle cx="56" cy="9" r="2.4" fill="#8B0000" />
      <circle cx="74" cy="14" r="2.4" fill="#8B0000" />
      <rect x="30" y="31" width="40" height="4" rx="1" fill="#8B0000" />
      <circle cx="40" cy="28" r="1.4" fill="#FDF8F0" />
      <circle cx="50" cy="28" r="1.4" fill="#FDF8F0" />
      <circle cx="60" cy="28" r="1.4" fill="#FDF8F0" />
      <circle cx="50" cy="63" r="25" fill="none" stroke="#8B0000" strokeWidth="3.2" />
      <circle cx="50" cy="63" r="20" fill="none" stroke="#8B0000" strokeWidth="1.4" />
      <text
        x="50"
        y="73.5"
        textAnchor="middle"
        fontFamily="'Playfair Display', Georgia, serif"
        fontWeight="700"
        fontSize="29"
        fill="#8B0000">
        
        G
      </text>
    </svg>);

}

interface LogoProps {
  tone?: 'light' | 'dark';
  showTagline?: boolean;
  markClassName?: string;
}

export function Logo({ tone = 'dark', showTagline = false, markClassName = 'h-10 w-10' }: LogoProps) {
  const nameColor = tone === 'light' ? 'text-white' : 'text-charcoal';
  const tagColor = tone === 'light' ? 'text-gold' : 'text-gold-deep';
  return (
    <span className="flex items-center gap-3">
      <LogoMark className={`${markClassName} shrink-0`} />
      <span className="flex flex-col leading-tight">
        <span className={`whitespace-nowrap font-serif text-lg font-semibold ${nameColor}`}>Toko Mas Gembira</span>
        {showTagline &&
        <span className={`text-[11px] font-medium tracking-wide ${tagColor}`}>
            Perhiasan Emas &amp; Perak Pilihan Terbaik
          </span>
        }
      </span>
    </span>);

}