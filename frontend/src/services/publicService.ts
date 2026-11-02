import { apiClient } from './apiClient';
export const publicService = { products: () => apiClient.get('/public/products'), promotions: () => apiClient.get('/public/promotions'), goldPrices: () => apiClient.get('/public/gold-prices'), storeInfo: () => apiClient.get('/public/store-info') };
