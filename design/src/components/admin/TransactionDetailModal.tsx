import React from 'react';
import { PrinterIcon } from 'lucide-react';
import { toast } from 'sonner';
import { Modal } from '../ui/Modal';
import { Button } from '../ui/Button';
import { Badge } from '../ui/Badge';
import { PaymentBadge } from './PaymentBadge';
import type { Transaction } from '../../types/transaction';
import { formatDateTime, formatRupiah, transactionTotal } from '../../utils/format';

interface TransactionDetailModalProps {
  transaction: Transaction | null;
  onClose: () => void;
}

export function TransactionDetailModal({ transaction, onClose }: TransactionDetailModalProps) {
  const subtotal = transaction ? transactionTotal(transaction.items) : 0;
  return (
    <Modal
      open={transaction !== null}
      onClose={onClose}
      title="Detail Transaksi"
      description={transaction?.id}
      size="lg"
      footer={
      <>
          <Button variant="light" onClick={onClose}>
            Tutup
          </Button>
          <Button onClick={() => toast.success('Nota dikirim ke printer')}>
            <PrinterIcon className="h-4 w-4" />
            Cetak Nota
          </Button>
        </>
      }>
      
      {transaction &&
      <div className="space-y-6">
          <dl className="grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
            <div>
              <dt className="text-charcoal/50">Tanggal</dt>
              <dd className="mt-0.5 font-medium text-charcoal">{formatDateTime(transaction.date)}</dd>
            </div>
            <div>
              <dt className="text-charcoal/50">Pelanggan</dt>
              <dd className="mt-0.5 font-medium text-charcoal">{transaction.customer}</dd>
            </div>
            <div>
              <dt className="text-charcoal/50">Kasir</dt>
              <dd className="mt-0.5 font-medium text-charcoal">{transaction.cashier}</dd>
            </div>
            <div>
              <dt className="text-charcoal/50">Status</dt>
              <dd className="mt-1">
                <Badge tone={transaction.status === 'Lunas' ? 'green' : 'yellow'} dot>
                  {transaction.status}
                </Badge>
              </dd>
            </div>
          </dl>

          <div className="overflow-hidden rounded-xl border border-line">
            <table className="w-full text-sm">
              <thead className="bg-ivory text-xs text-charcoal/55">
                <tr>
                  <th scope="col" className="px-4 py-2.5 text-left font-semibold">Produk</th>
                  <th scope="col" className="px-4 py-2.5 text-center font-semibold">Qty</th>
                  <th scope="col" className="px-4 py-2.5 text-right font-semibold">Harga</th>
                  <th scope="col" className="px-4 py-2.5 text-right font-semibold">Jumlah</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-line">
                {transaction.items.map((item) =>
              <tr key={item.productCode}>
                    <td className="px-4 py-3">
                      <p className="font-medium text-charcoal">{item.name}</p>
                      <p className="text-xs text-charcoal/45">{item.productCode}</p>
                    </td>
                    <td className="px-4 py-3 text-center">{item.qty}</td>
                    <td className="px-4 py-3 text-right">{formatRupiah(item.price)}</td>
                    <td className="px-4 py-3 text-right font-medium">{formatRupiah(item.price * item.qty)}</td>
                  </tr>
              )}
              </tbody>
            </table>
          </div>

          <div className="ml-auto max-w-xs space-y-2 text-sm">
            <div className="flex justify-between">
              <span className="text-charcoal/55">Subtotal</span>
              <span>{formatRupiah(subtotal)}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-charcoal/55">Diskon</span>
              <span className="text-crimson">− {formatRupiah(transaction.discount)}</span>
            </div>
            <div className="flex items-center justify-between">
              <span className="text-charcoal/55">Metode Bayar</span>
              <PaymentBadge method={transaction.method} />
            </div>
            <div className="flex justify-between border-t border-line pt-3">
              <span className="font-semibold text-charcoal">Total</span>
              <span className="font-serif text-xl font-semibold text-charcoal">{formatRupiah(subtotal - transaction.discount)}</span>
            </div>
          </div>
        </div>
      }
    </Modal>);

}