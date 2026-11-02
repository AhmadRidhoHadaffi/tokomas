import { useState } from 'react';
import type { Product } from '../types/product';
import type { Customer } from '../types/people';
import type { PaymentMethod } from '../types/transaction';

export interface DraftItem {
  product: Product;
  qty: number;
}

export type DraftCustomer = {kind: 'existing';customer: Customer;} | {kind: 'new';name: string;phone: string;};

export function useTransactionDraft() {
  const [step, setStep] = useState(1);
  const [customer, setCustomer] = useState<DraftCustomer | null>(null);
  const [items, setItems] = useState<DraftItem[]>([]);
  const [discount, setDiscount] = useState(0);
  const [method, setMethod] = useState<PaymentMethod>('Tunai');
  const [cashReceived, setCashReceived] = useState(0);
  const [saving, setSaving] = useState(false);
  const [savedId, setSavedId] = useState<string | null>(null);

  const subtotal = items.reduce((sum, item) => sum + item.product.price * item.qty, 0);
  const total = Math.max(0, subtotal - discount);
  const change = Math.max(0, cashReceived - total);

  const customerName =
  customer === null ? '' : customer.kind === 'existing' ? customer.customer.name : customer.name || 'Pelanggan baru';

  const addItem = (product: Product) => {
    setItems((current) => {
      const existing = current.find((i) => i.product.code === product.code);
      if (existing) {
        return current.map((i) =>
        i.product.code === product.code ? { ...i, qty: Math.min(i.qty + 1, product.stock) } : i
        );
      }
      return [...current, { product, qty: 1 }];
    });
  };

  const setQty = (code: string, qty: number) =>
  setItems((current) =>
  current.map((i) => i.product.code === code ? { ...i, qty: Math.max(1, Math.min(qty, i.product.stock)) } : i)
  );

  const removeItem = (code: string) => setItems((current) => current.filter((i) => i.product.code !== code));

  const canProceed = (() => {
    if (step === 1) {
      if (!customer) return false;
      if (customer.kind === 'new') return customer.name.trim().length >= 3 && customer.phone.trim().length >= 10;
      return true;
    }
    if (step === 2) return items.length > 0;
    if (step === 3) return discount <= subtotal && (method !== 'Tunai' || cashReceived >= total);
    return true;
  })();

  const save = () => {
    setSaving(true);
    window.setTimeout(() => {
      setSaving(false);
      setSavedId('TRX-20260925-015');
    }, 700);
  };

  const reset = () => {
    setStep(1);
    setCustomer(null);
    setItems([]);
    setDiscount(0);
    setMethod('Tunai');
    setCashReceived(0);
    setSavedId(null);
  };

  return {
    step,
    setStep,
    customer,
    setCustomer,
    customerName,
    items,
    addItem,
    setQty,
    removeItem,
    discount,
    setDiscount,
    method,
    setMethod,
    cashReceived,
    setCashReceived,
    subtotal,
    total,
    change,
    canProceed,
    saving,
    savedId,
    save,
    reset
  };
}

export type TransactionDraft = ReturnType<typeof useTransactionDraft>;