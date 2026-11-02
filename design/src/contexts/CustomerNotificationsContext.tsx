import React, { createContext, useContext, useMemo, useState } from 'react';
import type { CustomerNotification } from '../types/notification';
import { customerNotifications } from '../data/notifications';

interface CustomerNotificationsValue {
  notifications: CustomerNotification[];
  unreadCount: number;
  markRead: (id: string) => void;
  markAllRead: () => void;
}

const Ctx = createContext<CustomerNotificationsValue | null>(null);

export function CustomerNotificationsProvider({ children }: {children: React.ReactNode;}) {
  const [notifications, setNotifications] = useState<CustomerNotification[]>(customerNotifications);

  const value = useMemo<CustomerNotificationsValue>(
    () => ({
      notifications,
      unreadCount: notifications.filter((n) => !n.read).length,
      markRead: (id) => setNotifications((list) => list.map((n) => n.id === id ? { ...n, read: true } : n)),
      markAllRead: () => setNotifications((list) => list.map((n) => ({ ...n, read: true })))
    }),
    [notifications]
  );

  return <Ctx.Provider value={value}>{children}</Ctx.Provider>;
}

export function useCustomerNotifications(): CustomerNotificationsValue {
  const ctx = useContext(Ctx);
  if (!ctx) throw new Error('useCustomerNotifications must be used within CustomerNotificationsProvider');
  return ctx;
}