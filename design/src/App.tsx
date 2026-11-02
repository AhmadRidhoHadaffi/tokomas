import React, { useEffect } from 'react';
import { BrowserRouter, Navigate, Route, Routes, useLocation } from 'react-router-dom';
import { Toaster } from 'sonner';
import { LandingPage } from './pages/public/LandingPage';
import { LoginPage } from './pages/public/LoginPage';
import { RegisterPage } from './pages/public/RegisterPage';
import { DashboardLayout } from './components/dashboard/DashboardLayout';
import { CustomerLayout } from './components/customer/CustomerLayout';
import { OwnerDashboardPage } from './pages/admin/OwnerDashboardPage';
import { ProductsPage } from './pages/admin/ProductsPage';
import { TransactionsPage } from './pages/admin/TransactionsPage';
import { RecordTransactionPage } from './pages/admin/RecordTransactionPage';
import { LedgerPage } from './pages/admin/LedgerPage';
import { CustomOrdersPage } from './pages/admin/CustomOrdersPage';
import { CustomersPage } from './pages/admin/CustomersPage';
import { PromotionsPage } from './pages/admin/PromotionsPage';
import { EmployeesPage } from './pages/admin/EmployeesPage';
import { ReportsPage } from './pages/admin/ReportsPage';
import { SettingsPage } from './pages/admin/SettingsPage';
import { StaffDashboardPage } from './pages/staff/StaffDashboardPage';
import { CustomerHomePage } from './pages/customer/CustomerHomePage';
import { CustomerOrdersPage } from './pages/customer/CustomerOrdersPage';
import { CustomerTransactionsPage } from './pages/customer/CustomerTransactionsPage';
import { CustomerNotificationsPage } from './pages/customer/CustomerNotificationsPage';
import { CustomerProfilePage } from './pages/customer/CustomerProfilePage';

function ScrollToTop() {
  const { pathname } = useLocation();
  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);
  return null;
}

export function App() {
  return (
    <BrowserRouter>
      <ScrollToTop />
      <Toaster position="top-right" richColors closeButton toastOptions={{ style: { fontFamily: 'Poppins, sans-serif' } }} />
      <Routes>
        <Route path="/" element={<LandingPage />} />
        <Route path="/masuk" element={<LoginPage />} />
        <Route path="/daftar" element={<RegisterPage />} />

        <Route path="/owner" element={<DashboardLayout role="owner" />}>
          <Route index element={<OwnerDashboardPage />} />
          <Route path="produk" element={<ProductsPage role="owner" />} />
          <Route path="transaksi" element={<TransactionsPage />} />
          <Route path="transaksi/baru" element={<RecordTransactionPage />} />
          <Route path="pembukuan" element={<LedgerPage role="owner" />} />
          <Route path="pesanan-custom" element={<CustomOrdersPage role="owner" />} />
          <Route path="pelanggan" element={<CustomersPage />} />
          <Route path="promosi" element={<PromotionsPage />} />
          <Route path="karyawan" element={<EmployeesPage />} />
          <Route path="laporan" element={<ReportsPage />} />
          <Route path="pengaturan" element={<SettingsPage />} />
        </Route>

        <Route path="/karyawan" element={<DashboardLayout role="karyawan" />}>
          <Route index element={<StaffDashboardPage />} />
          <Route path="produk" element={<ProductsPage role="karyawan" />} />
          <Route path="transaksi" element={<RecordTransactionPage />} />
          <Route path="pembukuan" element={<LedgerPage role="karyawan" />} />
          <Route path="pesanan-custom" element={<CustomOrdersPage role="karyawan" />} />
          <Route path="pelanggan" element={<CustomersPage />} />
          <Route path="promosi" element={<PromotionsPage />} />
        </Route>

        <Route path="/pelanggan" element={<CustomerLayout />}>
          <Route index element={<CustomerHomePage />} />
          <Route path="pesanan" element={<CustomerOrdersPage />} />
          <Route path="riwayat" element={<CustomerTransactionsPage />} />
          <Route path="notifikasi" element={<CustomerNotificationsPage />} />
          <Route path="profil" element={<CustomerProfilePage />} />
        </Route>

        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </BrowserRouter>);

}