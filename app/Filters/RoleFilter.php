<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');
        if (! $role) {
            return redirect()->to('/masuk')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        if (! empty($arguments) && ! in_array($role, $arguments, true)) {
            return service('response')->setStatusCode(403)->setBody(view('pages/errors/403'));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
