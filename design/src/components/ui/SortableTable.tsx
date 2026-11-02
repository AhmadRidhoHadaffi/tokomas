import React, { useMemo, useState } from 'react';
import { ArrowDownIcon, ArrowUpIcon, ChevronsUpDownIcon } from 'lucide-react';
import { EmptyState } from './EmptyState';
import { Skeleton } from './Skeleton';

export interface Column<T> {
  key: string;
  header: string;
  render: (row: T, index: number) => React.ReactNode;
  sortValue?: (row: T) => string | number;
  align?: 'left' | 'right' | 'center';
  className?: string;
}

interface SortableTableProps<T> {
  columns: Column<T>[];
  rows: T[];
  rowKey: (row: T) => string;
  loading?: boolean;
  emptyTitle?: string;
  emptyDescription?: string;
  onRowClick?: (row: T) => void;
}

type SortState = {key: string;dir: 'asc' | 'desc';} | null;

const alignClass = { left: 'text-left', right: 'text-right', center: 'text-center' };

export function SortableTable<T>({
  columns,
  rows,
  rowKey,
  loading = false,
  emptyTitle = 'Belum ada data',
  emptyDescription,
  onRowClick
}: SortableTableProps<T>) {
  const [sort, setSort] = useState<SortState>(null);

  const sorted = useMemo(() => {
    if (!sort) return rows;
    const column = columns.find((c) => c.key === sort.key);
    if (!column?.sortValue) return rows;
    const getter = column.sortValue;
    return [...rows].sort((a, b) => {
      const va = getter(a);
      const vb = getter(b);
      const result = typeof va === 'number' && typeof vb === 'number' ? va - vb : String(va).localeCompare(String(vb), 'id');
      return sort.dir === 'asc' ? result : -result;
    });
  }, [rows, sort, columns]);

  const toggleSort = (key: string) => {
    setSort((current) => {
      if (!current || current.key !== key) return { key, dir: 'asc' };
      if (current.dir === 'asc') return { key, dir: 'desc' };
      return null;
    });
  };

  return (
    <div className="scrollbar-thin overflow-x-auto rounded-2xl border border-line bg-white">
      <table className="w-full min-w-[720px] text-sm">
        <thead>
          <tr className="border-b border-line bg-white">
            {columns.map((column) => {
              const active = sort?.key === column.key;
              const align = alignClass[column.align ?? 'left'];
              return (
                <th
                  key={column.key}
                  scope="col"
                  aria-sort={active ? sort?.dir === 'asc' ? 'ascending' : 'descending' : undefined}
                  className={`whitespace-nowrap px-4 py-3.5 text-xs font-semibold text-charcoal/55 ${align} ${column.className ?? ''}`}>
                  
                  {column.sortValue ?
                  <button
                    type="button"
                    onClick={() => toggleSort(column.key)}
                    className={`inline-flex items-center gap-1 rounded transition-colors duration-150 hover:text-charcoal ${active ? 'text-gold-deep' : ''}`}>
                    
                      {column.header}
                      {active ?
                    sort?.dir === 'asc' ?
                    <ArrowUpIcon className="h-3.5 w-3.5" /> :

                    <ArrowDownIcon className="h-3.5 w-3.5" /> :


                    <ChevronsUpDownIcon className="h-3.5 w-3.5 opacity-50" />
                    }
                    </button> :

                  column.header
                  }
                </th>);

            })}
          </tr>
        </thead>
        <tbody>
          {loading &&
          Array.from({ length: 5 }).map((_, i) =>
          <tr key={`skeleton-${i}`} className={i % 2 === 1 ? 'bg-ivory' : 'bg-white'}>
                {columns.map((column) =>
            <td key={column.key} className="px-4 py-4">
                    <Skeleton className="h-4 w-4/5" />
                  </td>
            )}
              </tr>
          )}
          {!loading && sorted.length === 0 &&
          <tr>
              <td colSpan={columns.length}>
                <EmptyState title={emptyTitle} description={emptyDescription} />
              </td>
            </tr>
          }
          {!loading &&
          sorted.map((row, index) =>
          <tr
            key={rowKey(row)}
            onClick={onRowClick ? () => onRowClick(row) : undefined}
            className={`border-b border-line/70 transition-colors duration-150 last:border-0 hover:bg-gold/10 ${
            index % 2 === 1 ? 'bg-ivory' : 'bg-white'} ${
            onRowClick ? 'cursor-pointer' : ''}`}>
            
                {columns.map((column) =>
            <td
              key={column.key}
              className={`whitespace-nowrap px-4 py-3.5 text-charcoal/85 ${alignClass[column.align ?? 'left']} ${column.className ?? ''}`}>
              
                    {column.render(row, index)}
                  </td>
            )}
              </tr>
          )}
        </tbody>
      </table>
    </div>);

}