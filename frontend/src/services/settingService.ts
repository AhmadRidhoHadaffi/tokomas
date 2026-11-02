import { apiClient } from './apiClient';
export const settingService = { store: () => apiClient.get('/settings/store'), updateStore: (data: unknown) => apiClient.put('/settings/store', data), goldPrices: () => apiClient.get('/settings/gold-prices'), updateGoldPrices: (prices: unknown) => apiClient.put('/settings/gold-prices', { prices }) };
