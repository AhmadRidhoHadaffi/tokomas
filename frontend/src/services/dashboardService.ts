import { apiClient } from './apiClient';
export const dashboardService = { get: () => apiClient.get('/dashboard') };
