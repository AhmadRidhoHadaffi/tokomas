<?php

namespace App\Cells;

class StatusBadgeCell
{
    public function show(string $status): string
    {
        $tone = match ($status) { 'Lunas', 'Selesai', 'Diambil', 'Terkirim' => 'green', 'Menunggu', 'Draf' => 'yellow', default => 'gold' };
        return view_cell('App\\Cells\\BadgeCell', ['label' => $status, 'tone' => $tone]);
    }
}
