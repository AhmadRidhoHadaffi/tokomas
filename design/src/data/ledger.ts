import type { LedgerEntry } from '../types/transaction';

export const ledgerEntries: LedgerEntry[] = [
{ id: 'BK-101', date: '2026-09-25', type: 'Pemasukan', category: 'Penjualan', description: 'Penjualan harian (3 transaksi)', amount: 17405000, recordedBy: 'Rina Marlina' },
{ id: 'BK-102', date: '2026-09-25', type: 'Pemasukan', category: 'DP Pesanan Custom', description: 'DP PC-2026-032 — Nurhayati', amount: 2500000, recordedBy: 'Rina Marlina' },
{ id: 'BK-103', date: '2026-09-24', type: 'Pemasukan', category: 'Penjualan', description: 'Penjualan harian (2 transaksi)', amount: 23750000, recordedBy: 'Dedi Saputra' },
{ id: 'BK-104', date: '2026-09-24', type: 'Pemasukan', category: 'Beli Emas', description: 'Selisih jual kembali emas pelanggan', amount: 1200000, recordedBy: 'Dedi Saputra' },
{ id: 'BK-105', date: '2026-09-23', type: 'Pemasukan', category: 'Penjualan', description: 'Penjualan harian (1 transaksi)', amount: 2850000, recordedBy: 'Dedi Saputra' },
{ id: 'BK-201', date: '2026-09-25', type: 'Pengeluaran', category: 'Bahan Baku', description: 'Pembelian emas batangan 50 gr', amount: 68500000, recordedBy: 'Rina Marlina' },
{ id: 'BK-202', date: '2026-09-24', type: 'Pengeluaran', category: 'Upah Pengrajin', description: 'Upah pembuatan PC-2026-031', amount: 850000, recordedBy: 'Dedi Saputra' },
{ id: 'BK-203', date: '2026-09-22', type: 'Pengeluaran', category: 'Operasional', description: 'Listrik & air toko September', amount: 1350000, recordedBy: 'Rina Marlina' },
{ id: 'BK-204', date: '2026-09-20', type: 'Pengeluaran', category: 'Sewa', description: 'Sewa kios Pasar Raya (bulanan)', amount: 4500000, recordedBy: 'Rina Marlina' },
{ id: 'BK-205', date: '2026-09-18', type: 'Pengeluaran', category: 'Operasional', description: 'Kotak perhiasan & kantong', amount: 620000, recordedBy: 'Dedi Saputra' }];


export const ledgerCategories = {
  Pemasukan: ['Penjualan', 'DP Pesanan Custom', 'Pelunasan Custom', 'Beli Emas', 'Lainnya'],
  Pengeluaran: ['Bahan Baku', 'Upah Pengrajin', 'Operasional', 'Sewa', 'Gaji Karyawan', 'Lainnya']
};