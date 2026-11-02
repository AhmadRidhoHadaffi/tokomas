<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
require APPPATH . 'Config/Routes/public.php';
require APPPATH . 'Config/Routes/auth.php';
require APPPATH . 'Config/Routes/owner.php';
require APPPATH . 'Config/Routes/staff.php';
require APPPATH . 'Config/Routes/customer.php';
require APPPATH . 'Config/Routes/ajax.php';
