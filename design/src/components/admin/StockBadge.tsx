import React from 'react';
import { Badge } from '../ui/Badge';
import type { StockLevel } from '../../types/product';

export function stockLevel(stock: number): StockLevel {
  if (stock <= 0) return 'habis';
  if (stock <= 3) return 'hampir';
  return 'tersedia';
}

export function StockBadge({ stock }: {stock: number;}) {
  const level = stockLevel(stock);
  if (level === 'habis') return <Badge tone="red" dot>Habis</Badge>;
  if (level === 'hampir') return <Badge tone="yellow" dot>Hampir habis · {stock}</Badge>;
  return <Badge tone="green" dot>Tersedia · {stock}</Badge>;
}