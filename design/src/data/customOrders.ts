import type { CustomOrder, CustomStatus } from '../types/customOrder';
import { images } from './images';

export const customStatusFlow: CustomStatus[] = ['Masuk', 'Diproses', 'Dalam Pembuatan', 'Selesai', 'Diambil'];

export const customOrders: CustomOrder[] = [
{
  id: 'PC-2026-031', customer: 'Siti Rahmawati', phone: '0812-7788-1020', product: 'Cincin Pasangan Ukir Nama', jewelryType: 'Cincin', model: 'Couple polos doff', engraving: 'Siti & Fajar', karat: '18K', weight: 6.5, estimatedPrice: 8900000, dp: 3000000, status: 'Dalam Pembuatan', date: '2026-09-15', dueDate: '2026-10-02', referenceImage: images.cincinPasangan, notes: 'Ukuran 12 dan 18. Ukiran di bagian dalam, font sambung.',
  history: [
  { status: 'Masuk', date: '2026-09-15T10:12:00', by: 'Rina Marlina' },
  { status: 'Diproses', date: '2026-09-16T09:30:00', by: 'Rina Marlina' },
  { status: 'Dalam Pembuatan', date: '2026-09-19T14:00:00', by: 'Dedi Saputra' }]

},
{
  id: 'PC-2026-030', customer: 'Andi Pratama', phone: '0813-6612-4431', product: 'Liontin Inisial A', jewelryType: 'Liontin', model: 'Inisial script', engraving: 'A', karat: '22K', weight: 3.0, estimatedPrice: 5100000, dp: 2000000, status: 'Diproses', date: '2026-09-21', dueDate: '2026-10-08', referenceImage: images.kalungEmas, notes: 'Rantai ikut dari toko, 45 cm.',
  history: [
  { status: 'Masuk', date: '2026-09-21T11:00:00', by: 'Dedi Saputra' },
  { status: 'Diproses', date: '2026-09-22T10:20:00', by: 'Dedi Saputra' }]

},
{
  id: 'PC-2026-029', customer: 'Yuliani Syafitri', phone: '0821-7001-2290', product: 'Gelang Ukir Rumah Gadang', jewelryType: 'Gelang', model: 'Bangle ukir', engraving: 'Yuli', karat: '22K', weight: 11.0, estimatedPrice: 18400000, dp: 8000000, status: 'Masuk', date: '2026-09-24', dueDate: '2026-10-20', referenceImage: images.gelangEmas, notes: 'Motif rumah gadang di sisi luar.',
  history: [{ status: 'Masuk', date: '2026-09-24T15:40:00', by: 'Rina Marlina' }]
},
{
  id: 'PC-2026-027', customer: 'Siti Rahmawati', phone: '0812-7788-1020', product: 'Anting Filigree Anak', jewelryType: 'Anting', model: 'Filigree mini', engraving: '-', karat: '18K', weight: 1.5, estimatedPrice: 2100000, dp: 1000000, status: 'Selesai', date: '2026-09-02', dueDate: '2026-09-20', referenceImage: images.antingEmas, notes: 'Untuk hadiah ulang tahun anak.',
  history: [
  { status: 'Masuk', date: '2026-09-02T09:00:00', by: 'Rina Marlina' },
  { status: 'Diproses', date: '2026-09-03T10:00:00', by: 'Rina Marlina' },
  { status: 'Dalam Pembuatan', date: '2026-09-06T13:00:00', by: 'Dedi Saputra' },
  { status: 'Selesai', date: '2026-09-19T16:30:00', by: 'Dedi Saputra' }]

},
{
  id: 'PC-2026-024', customer: 'Hendra Gunawan', phone: '0811-6630-7788', product: 'Cincin Batu Akik Emas', jewelryType: 'Cincin', model: 'Ikat batu', engraving: 'H.G', karat: '22K', weight: 7.2, estimatedPrice: 12300000, dp: 6000000, status: 'Diambil', date: '2026-08-18', dueDate: '2026-09-05', referenceImage: images.cincinEmas, notes: 'Batu dari pelanggan.',
  history: [
  { status: 'Masuk', date: '2026-08-18T10:00:00', by: 'Dedi Saputra' },
  { status: 'Diproses', date: '2026-08-19T09:00:00', by: 'Dedi Saputra' },
  { status: 'Dalam Pembuatan', date: '2026-08-22T11:00:00', by: 'Dedi Saputra' },
  { status: 'Selesai', date: '2026-09-04T15:00:00', by: 'Rina Marlina' },
  { status: 'Diambil', date: '2026-09-06T10:30:00', by: 'Rina Marlina' }]

},
{
  id: 'PC-2026-032', customer: 'Nurhayati', phone: '0852-6311-0098', product: 'Kalung Nama Arab', jewelryType: 'Kalung', model: 'Nama kaligrafi', engraving: 'Nur', karat: '18K', weight: 4.0, estimatedPrice: 5600000, dp: 2500000, status: 'Masuk', date: '2026-09-25', dueDate: '2026-10-15', referenceImage: images.kalungEmas, notes: 'Kaligrafi Arab, panjang rantai 42 cm.',
  history: [{ status: 'Masuk', date: '2026-09-25T09:15:00', by: 'Rina Marlina' }]
}];