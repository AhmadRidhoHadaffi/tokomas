<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Development-only button showcase.
 *
 * This page is intentionally not linked from the application navigation and
 * is only registered when ENVIRONMENT === 'development'.
 */
class DevButtonController extends BaseController
{
    public function index()
    {
        if (ENVIRONMENT !== 'development') {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('pages/dev/buttons');
    }
}
