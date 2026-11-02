export type NotificationKind = 'Status Pesanan' | 'Pembayaran' | 'Promosi';

export interface Promotion {
  id: string;
  title: string;
  message: string;
  target: string;
  recipients: number;
  sendDate: string;
  status: 'Terkirim' | 'Terjadwal' | 'Draf';
}

export interface SentNotification {
  id: string;
  recipient: string;
  kind: NotificationKind;
  content: string;
  time: string;
  delivered: boolean;
}

export interface CustomerNotification {
  id: string;
  kind: NotificationKind;
  title: string;
  body: string;
  time: string;
  read: boolean;
}