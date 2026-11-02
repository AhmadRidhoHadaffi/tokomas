export interface User {
  id: number;
  name: string;
  email: string;
  role: 'owner' | 'karyawan' | 'pelanggan';
}

export interface AuthResponse {
  user: User;
  token: string;
}

export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
  meta?: {
    page: number;
    per_page: number;
    total: number;
    total_pages: number;
  };
}
