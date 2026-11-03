<?php

namespace App\Cells;

class StockBadgeCell
{
    public function show($stock): string
    {
        $stock = (int) $stock;
        $status = $stock <= 0 ? 'habis' : ($stock <= 3 ? 'hampir' : 'tersedia');
        $tone = $stock <= 0 ? 'red' : ($stock <= 3 ? 'yellow' : 'green');
        $classes = ['red' => 'bg-red-100 text-red-700', 'yellow' => 'bg-yellow-100 text-yellow-700', 'green' => 'bg-green-100 text-green-700'][$tone] ?? 'bg-gray-100';
        return '<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ' . esc($classes) . '">' . esc($status) . '</span>';
    }
}
