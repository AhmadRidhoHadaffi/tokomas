import { apiClient } from './apiClient';
export const customerService = { list: (query = '') => apiClient.get(`/customers${query}`), detail: (id: number) => apiClient.get(`/customers/${id}`), update: (id: number, data: unknown) => apiClient.put(`/customers/${id}`, data) };
