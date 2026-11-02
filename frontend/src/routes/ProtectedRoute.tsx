import React from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

interface ProtectedRouteProps {
  children: React.ReactNode;
  allowedRoles: ('owner' | 'karyawan' | 'pelanggan')[];
}

export function ProtectedRoute({ children, allowedRoles }: ProtectedRouteProps) {
  const { user, isAuthenticated } = useAuth();

  if (!isAuthenticated) {
    return <Navigate to="/masuk" replace />;
  }

  if (!allowedRoles.includes(user!.role)) {
    // Redirect based on actual role
    if (user!.role === 'owner') return <Navigate to="/owner" replace />;
    if (user!.role === 'karyawan') return <Navigate to="/karyawan" replace />;
    if (user!.role === 'pelanggan') return <Navigate to="/pelanggan" replace />;
  }

  return <>{children}</>;
}
