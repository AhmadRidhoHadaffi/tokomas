import React from "react";
import { Link, NavLink } from "react-router-dom";
import { LogOutIcon, BoxIcon } from "lucide-react";
import { LogoMark } from "../brand/Logo";
interface NavItem {
  label: string;
  to: string;
  icon: BoxIcon;
  end?: boolean;
}
interface SidebarNavProps {
  items: NavItem[];
  compactOnTablet?: boolean;
  onNavigate?: () => void;
}
export function SidebarNav({
  items,
  compactOnTablet = false,
  onNavigate
}: SidebarNavProps) {
  const labelClass = compactOnTablet ? 'md:hidden lg:inline' : '';
  return <>
      <Link to="/" className={`flex h-16 items-center gap-3 border-b border-white/5 px-5 ${compactOnTablet ? 'md:justify-center md:px-0 lg:justify-start lg:px-5' : ''}`}>
        <LogoMark className="h-9 w-9 shrink-0" />
        <span className={`whitespace-nowrap font-serif text-[15px] font-semibold text-white ${labelClass}`}>Toko Mas Gembira</span>
      </Link>

      <nav aria-label="Menu dashboard" className="scrollbar-thin flex-1 overflow-y-auto px-3 py-5">
        <ul className="space-y-1">
          {items.map(({
          label,
          to,
          icon: Icon,
          end
        }) => <li key={to}>
              <NavLink to={to} end={end} onClick={onNavigate} title={label} className={({
            isActive
          }) => `relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors duration-150 ${compactOnTablet ? 'md:justify-center lg:justify-start' : ''} ${isActive ? 'bg-white/[0.06] text-gold' : 'text-white/65 hover:bg-white/[0.04] hover:text-white'}`}>
                {({
              isActive
            }) => <>
                    {isActive && <span className="absolute -left-3 top-1.5 bottom-1.5 w-1 rounded-r-full bg-gold" aria-hidden="true" />}
                    <Icon className="h-[18px] w-[18px] shrink-0" strokeWidth={1.75} />
                    <span className={`whitespace-nowrap ${labelClass}`}>{label}</span>
                  </>}
              </NavLink>
            </li>)}
        </ul>
      </nav>

      <div className="border-t border-white/5 p-3">
        <Link to="/masuk" title="Keluar" className={`flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-white/55 transition-colors duration-150 hover:bg-white/[0.04] hover:text-white ${compactOnTablet ? 'md:justify-center lg:justify-start' : ''}`}>
          <LogOutIcon className="h-[18px] w-[18px]" strokeWidth={1.75} />
          <span className={labelClass}>Keluar</span>
        </Link>
      </div>
    </>;
}