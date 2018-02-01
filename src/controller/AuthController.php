<?php

namespace Controller;

use Db\Dao\Impl\UserDaoImpl;
use Helper\Generator;
use Helper\Validator;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class AuthController
{
    public function getToken(Request $request, Response $response)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $user = json_decode($request->getBody(), true, $options = JSON_UNESCAPED_UNICODE);

        try {
            $userDao = new UserDaoImpl();
            if (!empty($user) && Validator::isUserValid($user)) {
                $user = $userDao->getByUsernameAndPassword($user['username'], $user['password']);

                if(empty($user)) {
                    $response = $response->withStatus(400);
                    $response->getBody()->write('{"message" : "Authentication failed"}');
                } else {
                    $token = Generator::generateToken($user['id'], $user['username']);
                    $response->getBody()->write('{"message": "Authentication succeeded", "token": "' . $token . '"}');
                }
            } else {
                $response = $response->withStatus(400);
                $response->getBody()->write('{"message" : "Bad Request"}');
            }
        } catch (\Exception $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write('{"message": "Internal Server Error"}');
        }
        return $response;
    }
}