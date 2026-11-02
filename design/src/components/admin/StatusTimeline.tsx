import React from 'react';
import type { StatusHistory } from '../../types/customOrder';
import { formatDateTime } from '../../utils/format';

export function StatusTimeline({ history }: {history: StatusHistory[];}) {
  const ordered = [...history].reverse();
  return (
    <ol className="relative space-y-4">
      {ordered.map((h, i) =>
      <li key={`${h.status}-${h.date}`} className="relative flex gap-3 pl-1">
          {i < ordered.length - 1 && <span className="absolute left-[9px] top-5 h-[calc(100%)] w-px bg-line" aria-hidden="true" />}
          <span className={`relative mt-1 h-3 w-3 shrink-0 rounded-full ring-4 ${i === 0 ? 'bg-gold ring-gold/20' : 'bg-charcoal/20 ring-white'}`} />
          <div>
            <p className={`text-sm font-medium ${i === 0 ? 'text-charcoal' : 'text-charcoal/70'}`}>{h.status}</p>
            <p className="text-xs text-charcoal/45">
              {formatDateTime(h.date)} · {h.by}
            </p>
          </div>
        </li>
      )}
    </ol>);

}