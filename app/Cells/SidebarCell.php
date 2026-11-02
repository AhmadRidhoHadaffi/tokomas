<?php

namespace App\Cells;

use Config\Navigation;

class SidebarCell
{
    public function show(string $role = 'owner'): string
    {
        $navigation = new Navigation();
        $items = $navigation->{$role} ?? [];
        return view('components/layout/sidebar', ['items' => $items, 'role' => $role]);
    }
}
