<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');
        if (! $role) {
            return null;
        }

        return redirect()->to(match ($role) {
            'owner' => '/owner',
            'karyawan' => '/karyawan',
            default => '/pelanggan',
        });
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
