export type CustomStatus = 'Masuk' | 'Diproses' | 'Dalam Pembuatan' | 'Selesai' | 'Diambil';

export interface StatusHistory {
  status: CustomStatus;
  date: string;
  by: string;
}

export interface CustomOrder {
  id: string;
  customer: string;
  phone: string;
  product: string;
  jewelryType: string;
  model: string;
  engraving: string;
  karat: string;
  weight: number;
  estimatedPrice: number;
  dp: number;
  status: CustomStatus;
  date: string;
  dueDate: string;
  referenceImage: string;
  notes: string;
  history: StatusHistory[];
}