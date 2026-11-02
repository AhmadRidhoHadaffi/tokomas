import React, { useState } from 'react';
import { EyeIcon, EyeOffIcon } from 'lucide-react';
import { inputClass } from '../ui/FormField';

type PasswordInputProps = Omit<React.InputHTMLAttributes<HTMLInputElement>, 'type'>;

export function PasswordInput(props: PasswordInputProps) {
  const [visible, setVisible] = useState(false);
  return (
    <div className="relative">
      <input {...props} type={visible ? 'text' : 'password'} className={`${inputClass} pr-11`} />
      <button
        type="button"
        onClick={() => setVisible((v) => !v)}
        aria-label={visible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'}
        className="absolute right-2 top-1/2 -translate-y-1/2 rounded-md p-1.5 text-charcoal/45 transition-colors duration-150 hover:text-charcoal">
        
        {visible ? <EyeOffIcon className="h-4 w-4" /> : <EyeIcon className="h-4 w-4" />}
      </button>
    </div>);

}