import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from '../contexts/AuthContext';
import { ProtectedRoute } from './ProtectedRoute';
import { DashboardLayout } from '../components/layout/DashboardLayout';
import { CustomerLayout } from '../components/layout/CustomerLayout';
import LandingPage from '../pages/public/LandingPage';
import LoginPage from '../pages/public/LoginPage';
import RegisterPage from '../pages/public/RegisterPage';
import OwnerDashboardPage from '../pages/owner/OwnerDashboardPage';
import ProductsPage from '../pages/owner/ProductsPage';
import TransactionsPage from '../pages/owner/TransactionsPage';
import FinancesPage from '../pages/owner/FinancesPage';
import CustomOrdersPage from '../pages/owner/CustomOrdersPage';
import CustomersPage from '../pages/owner/CustomersPage';
import PromotionsPage from '../pages/owner/PromotionsPage';
import EmployeesPage from '../pages/owner/EmployeesPage';
import ReportsPage from '../pages/owner/ReportsPage';
import SettingsPage from '../pages/owner/SettingsPage';
import StaffDashboardPage from '../pages/staff/StaffDashboardPage';
import StaffProductsPage from '../pages/staff/StaffProductsPage';
import StaffTransactionsPage from '../pages/staff/StaffTransactionsPage';
import StaffFinancesPage from '../pages/staff/StaffFinancesPage';
import StaffCustomOrdersPage from '../pages/staff/StaffCustomOrdersPage';
import StaffCustomersPage from '../pages/staff/StaffCustomersPage';
import StaffPromotionsPage from '../pages/staff/StaffPromotionsPage';
import CustomerHomePage from '../pages/customer/CustomerHomePage';
import CustomerOrdersPage from '../pages/customer/CustomerOrdersPage';
import CustomerTransactionsPage from '../pages/customer/CustomerTransactionsPage';
import CustomerNotificationsPage from '../pages/customer/CustomerNotificationsPage';
import CustomerProfilePage from '../pages/customer/CustomerProfilePage';

export function AppRoutes() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          <Route path="/" element={<LandingPage />} />
          <Route path="/masuk" element={<LoginPage />} />
          <Route path="/daftar" element={<RegisterPage />} />
          <Route path="/owner" element={<ProtectedRoute allowedRoles={['owner']}><DashboardLayout role="owner" /></ProtectedRoute>}>
            <Route index element={<OwnerDashboardPage />} />
            <Route path="produk" element={<ProductsPage />} />
            <Route path="transaksi" element={<TransactionsPage />} />
            <Route path="pembukuan" element={<FinancesPage />} />
            <Route path="pesanan-custom" element={<CustomOrdersPage />} />
            <Route path="pelanggan" element={<CustomersPage />} />
            <Route path="promosi" element={<PromotionsPage />} />
            <Route path="karyawan" element={<EmployeesPage />} />
            <Route path="laporan" element={<ReportsPage />} />
            <Route path="pengaturan" element={<SettingsPage />} />
          </Route>
          <Route path="/karyawan" element={<ProtectedRoute allowedRoles={['karyawan']}><DashboardLayout role="karyawan" /></ProtectedRoute>}>
            <Route index element={<StaffDashboardPage />} />
            <Route path="produk" element={<StaffProductsPage />} />
            <Route path="transaksi" element={<StaffTransactionsPage />} />
            <Route path="pembukuan" element={<StaffFinancesPage />} />
            <Route path="pesanan-custom" element={<StaffCustomOrdersPage />} />
            <Route path="pelanggan" element={<StaffCustomersPage />} />
            <Route path="promosi" element={<StaffPromotionsPage />} />
          </Route>
          <Route path="/pelanggan" element={<ProtectedRoute allowedRoles={['pelanggan']}><CustomerLayout /></ProtectedRoute>}>
            <Route index element={<CustomerHomePage />} />
            <Route path="pesanan" element={<CustomerOrdersPage />} />
            <Route path="riwayat" element={<CustomerTransactionsPage />} />
            <Route path="notifikasi" element={<CustomerNotificationsPage />} />
            <Route path="profil" element={<CustomerProfilePage />} />
          </Route>
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}
