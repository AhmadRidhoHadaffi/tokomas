export type MetalType = 'Emas' | 'Perak';

export type ProductCategory = 'Cincin' | 'Kalung' | 'Gelang' | 'Anting' | 'Liontin';

export interface Product {
  code: string;
  name: string;
  category: ProductCategory;
  metal: MetalType;
  weight: number;
  karat: string;
  size: string;
  model: string;
  price: number;
  stock: number;
  image: string;
}

export type StockLevel = 'tersedia' | 'hampir' | 'habis';