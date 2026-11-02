import type { CustomerNotification, Promotion, SentNotification } from '../types/notification';

export const promotions: Promotion[] = [
{ id: 'PRM-012', title: 'Diskon Ongkos Bikin 20% — Bulan Maulid', message: 'Nikmati potongan 20% ongkos pembuatan untuk semua pesanan custom hingga 10 Oktober 2026.', target: 'Semua Pelanggan', recipients: 248, sendDate: '2026-09-20T09:00:00', status: 'Terkirim' },
{ id: 'PRM-013', title: 'Cincin Pasangan Gratis Ukir Nama', message: 'Pesan cincin pasangan 18K dan dapatkan ukiran nama gratis. Berlaku akhir pekan ini.', target: 'Pelanggan Pesanan Custom', recipients: 62, sendDate: '2026-09-27T10:00:00', status: 'Terjadwal' },
{ id: 'PRM-011', title: 'Tukar Tambah Emas Lama', message: 'Bawa emas lama Anda, tukar tambah dengan model terbaru tanpa potongan tambahan.', target: 'Pelanggan Setia', recipients: 91, sendDate: '2026-09-05T09:00:00', status: 'Terkirim' },
{ id: 'PRM-014', title: 'Koleksi Perak Rumah Gadang', message: 'Koleksi liontin perak motif Rumah Gadang kini tersedia.', target: 'Semua Pelanggan', recipients: 0, sendDate: '2026-10-01T09:00:00', status: 'Draf' }];


export const sentNotifications: SentNotification[] = [
{ id: 'NT-501', recipient: 'Siti Rahmawati', kind: 'Status Pesanan', content: 'Pesanan PC-2026-031 kini Dalam Pembuatan.', time: '2026-09-19T14:01:00', delivered: true },
{ id: 'NT-502', recipient: 'Andi Pratama', kind: 'Status Pesanan', content: 'Pesanan PC-2026-030 sedang Diproses.', time: '2026-09-22T10:21:00', delivered: true },
{ id: 'NT-503', recipient: 'Rizky Maulana', kind: 'Pembayaran', content: 'Menunggu konfirmasi transfer TRX-20260922-015.', time: '2026-09-22T15:25:00', delivered: true },
{ id: 'NT-504', recipient: '248 pelanggan', kind: 'Promosi', content: 'Diskon Ongkos Bikin 20% — Bulan Maulid', time: '2026-09-20T09:00:00', delivered: true },
{ id: 'NT-505', recipient: 'Nurhayati', kind: 'Status Pesanan', content: 'Pesanan PC-2026-032 telah diterima.', time: '2026-09-25T09:16:00', delivered: false }];


export const customerNotifications: CustomerNotification[] = [
{ id: 'CN-1', kind: 'Status Pesanan', title: 'Pesanan sedang dibuat', body: 'Cincin Pasangan Ukir Nama (PC-2026-031) kini dalam tahap pembuatan oleh pengrajin kami.', time: '2026-09-19T14:01:00', read: false },
{ id: 'CN-2', kind: 'Promosi', title: 'Diskon Ongkos Bikin 20%', body: 'Potongan 20% ongkos pembuatan untuk semua pesanan custom hingga 10 Oktober 2026.', time: '2026-09-20T09:00:00', read: false },
{ id: 'CN-3', kind: 'Pembayaran', title: 'Pembayaran diterima', body: 'Pembayaran QRIS sebesar Rp 3.140.000 untuk TRX-20260925-014 telah kami terima. Terima kasih!', time: '2026-09-25T14:23:00', read: false },
{ id: 'CN-4', kind: 'Status Pesanan', title: 'Pesanan siap diambil', body: 'Anting Filigree Anak (PC-2026-027) sudah selesai. Silakan ambil di toko dengan menunjukkan nomor pesanan.', time: '2026-09-19T16:31:00', read: true },
{ id: 'CN-5', kind: 'Promosi', title: 'Tukar tambah emas lama', body: 'Tukar tambah emas lama Anda dengan model terbaru tanpa potongan tambahan.', time: '2026-09-05T09:00:00', read: true }];