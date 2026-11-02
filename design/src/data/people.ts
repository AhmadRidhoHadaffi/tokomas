import type { Customer, Employee } from '../types/people';

export const customers: Customer[] = [
{ id: 'PLG-001', name: 'Siti Rahmawati', phone: '0812-7788-1020', email: 'siti.rahma@gmail.com', address: 'Jl. Veteran No. 21, Padang Barat', totalTransactions: 6, totalSpent: 21450000, joined: '2025-03-12' },
{ id: 'PLG-002', name: 'Andi Pratama', phone: '0813-6612-4431', email: 'andi.pratama@gmail.com', address: 'Jl. Khatib Sulaiman No. 8, Padang Utara', totalTransactions: 3, totalSpent: 18300000, joined: '2025-07-02' },
{ id: 'PLG-003', name: 'Yuliani Syafitri', phone: '0821-7001-2290', email: 'yuli.syafitri@yahoo.com', address: 'Jl. Sawahan Dalam No. 4, Padang Timur', totalTransactions: 4, totalSpent: 9820000, joined: '2025-11-19' },
{ id: 'PLG-004', name: 'Hendra Gunawan', phone: '0811-6630-7788', email: 'hendra.g@gmail.com', address: 'Jl. Ujung Gurun No. 55, Padang Barat', totalTransactions: 9, totalSpent: 64750000, joined: '2024-12-01' },
{ id: 'PLG-005', name: 'Nurhayati', phone: '0852-6311-0098', email: 'nurhayati88@gmail.com', address: 'Jl. Andalas No. 17, Padang Timur', totalTransactions: 2, totalSpent: 2850000, joined: '2026-02-14' },
{ id: 'PLG-006', name: 'Rizky Maulana', phone: '0812-6600-3321', email: 'rizky.maulana@gmail.com', address: 'Jl. Siteba Raya No. 3, Nanggalo', totalTransactions: 1, totalSpent: 5280000, joined: '2026-09-01' },
{ id: 'PLG-007', name: 'Fauzan Hakim', phone: '0813-7455-9012', email: 'fauzan.hakim@gmail.com', address: 'Jl. Belanti Raya No. 9, Padang Utara', totalTransactions: 5, totalSpent: 32400000, joined: '2025-05-23' }];


export const employees: Employee[] = [
{ id: 'KRY-001', name: 'Rina Marlina', email: 'rina@tokomasgembira.id', active: true, createdAt: '2024-11-04' },
{ id: 'KRY-002', name: 'Dedi Saputra', email: 'dedi@tokomasgembira.id', active: true, createdAt: '2025-01-15' },
{ id: 'KRY-003', name: 'Melati Putri', email: 'melati@tokomasgembira.id', active: false, createdAt: '2025-06-20' }];