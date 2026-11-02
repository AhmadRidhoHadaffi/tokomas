<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Jwt extends BaseConfig
{
    public string $secret;
    public int $ttl;
    public string $issuer;

    public function __construct()
    {
        $this->secret = env('jwt.secret', '');
        $this->ttl = (int) env('jwt.ttl', 604800);
        $this->issuer = env('jwt.issuer', 'toko-mas-gembira');

        if (empty($this->secret)) {
            throw new \RuntimeException('JWT secret not configured');
        }
    }
}
