<?php

namespace App\Libraries;

use Config\Jwt as JwtConfig;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtLibrary
{
    protected JwtConfig $config;

    public function __construct()
    {
        $this->config = new JwtConfig();
    }

    public function encode(array $payload): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->config->ttl;

        $token = [
            'iss' => $this->config->issuer,
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => $payload,
        ];

        return JWT::encode($token, $this->config->secret, 'HS256');
    }

    public function decode(string $token): ?object
    {
        try {
            $decoded = JWT::decode($token, new Key($this->config->secret, 'HS256'));
            return $decoded->data ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function verify(string $token): bool
    {
        return $this->decode($token) !== null;
    }
}
