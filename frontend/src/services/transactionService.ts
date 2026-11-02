import { apiClient } from './apiClient';
export const transactionService = { list: (query = '') => apiClient.get(`/transactions${query}`), detail: (id: number) => apiClient.get(`/transactions/${id}`), create: (data: unknown) => apiClient.post('/transactions', data) };
