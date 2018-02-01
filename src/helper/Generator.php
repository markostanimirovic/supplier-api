<?php

namespace Helper;

use \Tuupola\Base62Proxy;
use \Firebase\JWT\JWT;

class Generator
{
    public static function generateToken(int $id, string $username)
    {
        $secret = "supplier-api";
        $jti = Base62Proxy::encode(random_bytes(16));
        $time = time();
        $payload = [
            'iss' => $username,
            'jti' => $jti,
            'iat' => $time,
            'exp' => ($time + 86400),
            'uid' => $id,
        ];
        $token = JWT::encode($payload, $secret, 'HS256');
        return $token;
    }
}