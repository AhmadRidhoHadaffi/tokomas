import { apiClient } from './apiClient';
export const notificationService = { list: (query = '') => apiClient.get(`/notifications${query}`), unreadCount: () => apiClient.get('/notifications/unread-count'), markRead: (id: number) => apiClient.put(`/notifications/${id}/read`), markAllRead: () => apiClient.put('/notifications/mark-all-read') };
