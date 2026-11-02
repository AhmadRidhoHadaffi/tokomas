import React, { useEffect, useId, useState } from 'react';
import { ImagePlusIcon, XIcon } from 'lucide-react';

interface UploadBoxProps {
  label: string;
  hint?: string;
  initialUrl?: string;
}

export function UploadBox({ label, hint = 'PNG atau JPG, maks. 5 MB', initialUrl }: UploadBoxProps) {
  const id = useId();
  const [preview, setPreview] = useState<string | undefined>(initialUrl);
  const [objectUrl, setObjectUrl] = useState<string | null>(null);

  useEffect(() => {
    return () => {
      if (objectUrl) URL.revokeObjectURL(objectUrl);
    };
  }, [objectUrl]);

  const onChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (!file) return;
    const url = URL.createObjectURL(file);
    setObjectUrl(url);
    setPreview(url);
  };

  return (
    <div>
      <span className="mb-1.5 block text-sm font-medium text-charcoal/80">{label}</span>
      {preview ?
      <div className="relative h-36 overflow-hidden rounded-xl border border-line">
          <img src={preview} alt="Pratinjau unggahan" className="h-full w-full object-cover" />
          <button
          type="button"
          onClick={() => setPreview(undefined)}
          aria-label="Hapus gambar"
          className="absolute right-2 top-2 rounded-full bg-charcoal/70 p-1.5 text-white transition-colors duration-150 hover:bg-charcoal">
          
            <XIcon className="h-4 w-4" />
          </button>
        </div> :

      <label
        htmlFor={id}
        className="flex h-36 cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gold/40 bg-ivory/60 text-center transition-colors duration-150 hover:border-gold hover:bg-ivory">
        
          <ImagePlusIcon className="h-7 w-7 text-gold-dark" strokeWidth={1.5} />
          <span className="text-sm font-medium text-charcoal/80">Klik untuk unggah gambar</span>
          <span className="text-xs text-charcoal/50">{hint}</span>
        </label>
      }
      <input id={id} type="file" accept="image/*" className="sr-only" onChange={onChange} />
    </div>);

}