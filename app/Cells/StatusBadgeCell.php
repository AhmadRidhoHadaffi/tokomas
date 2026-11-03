<?php

namespace App\Cells;

class StatusBadgeCell
{
    public function show(string $status): string
    {
        $tone = match ($status) { 'Lunas', 'Selesai', 'Diambil', 'Terkirim' => 'green', 'Menunggu', 'Draf' => 'yellow', default => 'gold' };
        $classes = ['green' => 'bg-green-100 text-green-700', 'yellow' => 'bg-yellow-100 text-yellow-700', 'gold' => 'bg-gold-light text-gold-deep'][$tone] ?? 'bg-gray-100';
        return '<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ' . esc($classes) . '">' . esc($status) . '</span>';
    }
}
