import { apiClient } from './apiClient';
import type { ApiResponse, AuthResponse, User } from '../types';
export const authService = {
  login: (email: string, password: string) => apiClient.post<ApiResponse<AuthResponse>>('/auth/login', { email, password }),
  register: (data: { name: string; email: string; phone: string; password: string; address?: string }) => apiClient.post<ApiResponse<AuthResponse>>('/auth/register', data),
  me: () => apiClient.get<ApiResponse<User>>('/auth/me'),
  logout: () => apiClient.post<ApiResponse<null>>('/auth/logout'),
};
