<?php

namespace App\Cells;

class StockBadgeCell
{
    public function show(int $stock): string
    {
        $status = $stock <= 0 ? 'habis' : ($stock <= 3 ? 'hampir' : 'tersedia');
        return view_cell('App\\Cells\\BadgeCell', ['label' => $status, 'tone' => $stock <= 0 ? 'red' : ($stock <= 3 ? 'yellow' : 'green')]);
    }
}
