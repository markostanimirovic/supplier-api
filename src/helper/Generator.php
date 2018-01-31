<?php

namespace Helper;

use \Tuupola\Base62Proxy;
use \Firebase\JWT\JWT;
use \DateTime;

class Generator
{
    public static function generateToken()
    {

        //@TODO: implement function
//        $now = new DateTime();
//        $future = new DateTime("now +2 minutes");
//        $jti = Base62Proxy::encode(random_bytes(16));
        $secret = "supplier-api";
//        $payload = [
//            "iss" => "USERNAME",
//            "jti" => $jti,
//            "iat" => $now,
////            "nbf" => $future->getTimeStamp("now -2 hours"),
//            "exp" => $now + 2000
//        ];

        $time = time();
        $payload = [
            "iss" => 'Johnny Cash',     // you may load user data when you validate, as user database name
            "iat" => $time,             // when user claim this token/request
            "exp" => ($time + 600000),  // 7 days expiration
            "uid" => '123',             // it's a custom key in the token, kept user id for some purpose
        ];

        $token = JWT::encode($payload, $secret, "HS256");
        return $token;
    }
}



