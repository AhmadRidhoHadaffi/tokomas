import React, { useState } from 'react';
import { FileSpreadsheetIcon, FileTextIcon, Loader2Icon, SparklesIcon } from 'lucide-react';
import { toast } from 'sonner';
import { Button } from '../../components/ui/Button';
import { Field, SelectInput, TextInput } from '../../components/ui/FormField';
import { EmptyState } from '../../components/ui/EmptyState';
import { Skeleton } from '../../components/ui/Skeleton';
import { ReportResult, type ReportType } from '../../components/admin/ReportResult';
import { formatDate } from '../../utils/format';

const reportTypes: ReportType[] = ['Penjualan', 'Pembukuan', 'Stok', 'Pesanan Custom'];

export function ReportsPage() {
  const [type, setType] = useState<ReportType>('Penjualan');
  const [from, setFrom] = useState('2026-09-01');
  const [to, setTo] = useState('2026-09-25');
  const [generating, setGenerating] = useState(false);
  const [result, setResult] = useState<{type: ReportType;from: string;to: string;} | null>(null);

  const generate = () => {
    if (from > to) {
      toast.error('Tanggal mulai harus sebelum tanggal selesai');
      return;
    }
    setGenerating(true);
    window.setTimeout(() => {
      setResult({ type, from, to });
      setGenerating(false);
    }, 700);
  };

  return (
    <div className="space-y-6">
      <div className="rounded-2xl border border-line bg-white p-5">
        <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-[1fr_1fr_1fr_auto] xl:items-end">
          <Field label="Jenis Laporan" htmlFor="r-type">
            <SelectInput id="r-type" value={type} onChange={(e) => setType(e.target.value as ReportType)}>
              {reportTypes.map((t) =>
              <option key={t}>{t}</option>
              )}
            </SelectInput>
          </Field>
          <Field label="Tanggal Mulai" htmlFor="r-from">
            <TextInput id="r-from" type="date" value={from} onChange={(e) => setFrom(e.target.value)} />
          </Field>
          <Field label="Tanggal Selesai" htmlFor="r-to">
            <TextInput id="r-to" type="date" value={to} onChange={(e) => setTo(e.target.value)} />
          </Field>
          <Button onClick={generate} disabled={generating} className="md:col-span-2 xl:col-span-1">
            {generating ? <Loader2Icon className="h-4 w-4 animate-spin" /> : <SparklesIcon className="h-4 w-4" />}
            Generate Laporan
          </Button>
        </div>
      </div>

      {generating &&
      <div className="space-y-4">
          <div className="grid gap-4 sm:grid-cols-3">
            {[0, 1, 2].map((i) =>
          <Skeleton key={i} className="h-24 rounded-2xl" />
          )}
          </div>
          <Skeleton className="h-72 rounded-2xl" />
        </div>
      }

      {!generating && !result &&
      <div className="rounded-2xl border border-dashed border-gold/40 bg-white">
          <EmptyState title="Belum ada laporan" description="Pilih jenis laporan dan periode, lalu klik Generate Laporan." />
        </div>
      }

      {!generating && result &&
      <>
          <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <h2 className="font-serif text-xl font-semibold text-charcoal">Laporan {result.type}</h2>
              <p className="text-sm text-charcoal/55">
                {formatDate(result.from)} – {formatDate(result.to)}
              </p>
            </div>
            <div className="flex gap-2">
              <Button variant="light" onClick={() => toast.success('Laporan PDF diunduh')}>
                <FileTextIcon className="h-4 w-4 text-crimson" />
                Unduh PDF
              </Button>
              <Button variant="light" onClick={() => toast.success('Laporan Excel diunduh')}>
                <FileSpreadsheetIcon className="h-4 w-4 text-emerald-700" />
                Unduh Excel
              </Button>
            </div>
          </div>
          <ReportResult type={result.type} from={result.from} to={result.to} />
        </>
      }
    </div>);

}