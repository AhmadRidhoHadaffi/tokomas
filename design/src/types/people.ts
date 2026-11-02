export type Role = 'owner' | 'karyawan' | 'pelanggan';

export interface Customer {
  id: string;
  name: string;
  phone: string;
  email: string;
  address: string;
  totalTransactions: number;
  totalSpent: number;
  joined: string;
}

export interface Employee {
  id: string;
  name: string;
  email: string;
  active: boolean;
  createdAt: string;
}