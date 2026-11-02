import { apiClient } from './apiClient';
export const productService = { list: (query = '') => apiClient.get(`/products${query}`), detail: (id: number) => apiClient.get(`/products/${id}`), create: (data: unknown) => apiClient.post('/products', data), update: (id: number, data: unknown) => apiClient.put(`/products/${id}`, data), remove: (id: number) => apiClient.delete(`/products/${id}`) };
