<?php

namespace App\Cells;

class StatusBadgeCell
{
    public function show(string $status): string
    {
        // Map status to badge tone
        $toneMap = [
            // Custom order status
            'Masuk' => 'blue',
            'Diproses' => 'yellow',
            'Dalam Pembuatan' => 'orange',
            'Selesai' => 'green',
            'Diambil' => 'grey',
            // Payment status
            'Lunas' => 'green',
            'Menunggu' => 'yellow',
            // Promotion status
            'Draf' => 'grey',
            'Terjadwal' => 'blue',
            'Terkirim' => 'green',
        ];

        $tone = $toneMap[$status] ?? 'gold';
        
        return badge([
            'tone' => $tone,
            'text' => $status,
            'dot' => true,
        ]);
    }
}
