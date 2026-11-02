export type PaymentMethod = 'Tunai' | 'Transfer' | 'QRIS';

export interface TransactionItem {
  productCode: string;
  name: string;
  qty: number;
  price: number;
}

export interface Transaction {
  id: string;
  date: string;
  cashier: string;
  customer: string;
  items: TransactionItem[];
  discount: number;
  method: PaymentMethod;
  status: 'Lunas' | 'Menunggu';
}

export type LedgerType = 'Pemasukan' | 'Pengeluaran';

export interface LedgerEntry {
  id: string;
  date: string;
  type: LedgerType;
  category: string;
  description: string;
  amount: number;
  recordedBy: string;
}