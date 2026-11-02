import {
  BarChart3Icon,
  BellIcon,
  BookOpenIcon,
  ClipboardListIcon,
  GemIcon,
  GiftIcon,
  HomeIcon,
  PackageIcon,
  ReceiptTextIcon,
  SettingsIcon,
  UserIcon,
  UserRoundIcon,
  UsersIcon,
  WalletIcon } from
'lucide-react';

export const ownerNav = [
{ label: 'Dashboard', to: '/owner', icon: HomeIcon, end: true },
{ label: 'Produk & Stok', to: '/owner/produk', icon: PackageIcon },
{ label: 'Transaksi Penjualan', to: '/owner/transaksi', icon: WalletIcon },
{ label: 'Pembukuan', to: '/owner/pembukuan', icon: BookOpenIcon },
{ label: 'Pesanan Custom', to: '/owner/pesanan-custom', icon: GemIcon },
{ label: 'Pelanggan', to: '/owner/pelanggan', icon: UserIcon },
{ label: 'Promosi & Notifikasi', to: '/owner/promosi', icon: GiftIcon },
{ label: 'Manajemen Karyawan', to: '/owner/karyawan', icon: UsersIcon },
{ label: 'Laporan', to: '/owner/laporan', icon: BarChart3Icon },
{ label: 'Pengaturan', to: '/owner/pengaturan', icon: SettingsIcon }];


export const karyawanNav = [
{ label: 'Dashboard', to: '/karyawan', icon: HomeIcon, end: true },
{ label: 'Produk & Stok', to: '/karyawan/produk', icon: PackageIcon },
{ label: 'Catat Transaksi', to: '/karyawan/transaksi', icon: ReceiptTextIcon },
{ label: 'Pembukuan', to: '/karyawan/pembukuan', icon: BookOpenIcon },
{ label: 'Pesanan Custom', to: '/karyawan/pesanan-custom', icon: GemIcon },
{ label: 'Pelanggan', to: '/karyawan/pelanggan', icon: UserIcon },
{ label: 'Promosi & Notifikasi', to: '/karyawan/promosi', icon: GiftIcon }];


export const pelangganNav = [
{ label: 'Beranda', to: '/pelanggan', icon: HomeIcon, end: true },
{ label: 'Pesanan', to: '/pelanggan/pesanan', icon: GemIcon },
{ label: 'Riwayat', to: '/pelanggan/riwayat', icon: ClipboardListIcon },
{ label: 'Notifikasi', to: '/pelanggan/notifikasi', icon: BellIcon },
{ label: 'Profil', to: '/pelanggan/profil', icon: UserRoundIcon }];


export const sessionUsers = {
  owner: { name: 'Hj. Rosmaniar', role: 'Pemilik Toko', email: 'owner@tokomasgembira.id' },
  karyawan: { name: 'Rina Marlina', role: 'Karyawan · Kasir', email: 'rina@tokomasgembira.id' },
  pelanggan: { name: 'Siti Rahmawati', role: 'Pelanggan', email: 'siti.rahma@gmail.com', phone: '0812-7788-1020' }
};