<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = service('session');
        helper(['form', 'url']);
    }

    protected function render(string $view, array $data = [])
    {
        return view($view, $data);
    }

    protected function userId(): ?int
    {
        return $this->session->get('user_id') ? (int) $this->session->get('user_id') : null;
    }
}
