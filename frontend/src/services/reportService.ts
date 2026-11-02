import { apiClient } from './apiClient';
export const reportService = { sales: (query = '') => apiClient.get(`/reports/sales${query}`), finances: (query = '') => apiClient.get(`/reports/finances${query}`), stock: () => apiClient.get('/reports/stock'), customOrders: (query = '') => apiClient.get(`/reports/custom-orders${query}`) };
