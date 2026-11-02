<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ThrottleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $capacity = isset($arguments[0]) ? (int) $arguments[0] : 5;
        $seconds = isset($arguments[1]) ? (int) $arguments[1] : 60;
        $key = 'api-rate-' . sha1($request->getIPAddress() . '|' . $request->getMethod() . '|' . $request->getUri()->getPath());

        if (! service('throttler')->check($key, $capacity, $seconds)) {
            return service('response')
                ->setStatusCode(429)
                ->setHeader('Retry-After', (string) $seconds)
                ->setJSON([
                    'success' => false,
                    'message' => 'Terlalu banyak permintaan. Coba lagi nanti.',
                ]);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
