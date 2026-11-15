<?php

namespace App\Cells;

class StockBadgeCell
{
    public function show($stock): string
    {
        $stock = (int) $stock;
        
        if ($stock <= 0) {
            $tone = 'red';
            $text = 'Habis';
        } elseif ($stock <= 3) {
            $tone = 'yellow';
            $text = 'Hampir Habis';
        } else {
            $tone = 'green';
            $text = 'Tersedia';
        }

        return badge([
            'tone' => $tone,
            'text' => $text,
            'dot' => true,
        ]);
    }
}
