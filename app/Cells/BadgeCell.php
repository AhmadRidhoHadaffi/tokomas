<?php

namespace App\Cells;

class BadgeCell
{
    public function show(string $label, string $tone = 'gold'): string
    {
        $classes = ['green' => 'bg-green-100 text-green-700', 'yellow' => 'bg-yellow-100 text-yellow-700', 'red' => 'bg-red-100 text-red-700', 'gold' => 'bg-gold-light text-gold-deep'][$tone] ?? 'bg-gray-100 text-gray-700';
        return '<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ' . esc($classes) . '">' . esc($label) . '</span>';
    }
}
