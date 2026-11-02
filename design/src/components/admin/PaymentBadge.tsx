import React from 'react';
import { Badge } from '../ui/Badge';
import type { PaymentMethod } from '../../types/transaction';

const tone = { Tunai: 'grey', Transfer: 'blue', QRIS: 'gold' } as const;

export function PaymentBadge({ method }: {method: PaymentMethod;}) {
  return <Badge tone={tone[method]}>{method}</Badge>;
}