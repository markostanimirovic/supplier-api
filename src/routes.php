<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Db\DBBroker;
use \Helper\Validator;

$app->get('/products[/]', 'ProductController:getAll');

$app->post('/products[/]', 'ProductController:insert');

$app->put('/products/{id}[/]', 'ProductController:update');


//@TODO: implement AuthController
$app->post('/token[/]', function (Request $request, Response $response) {
    $response = $response->withHeader('Content-type', 'application/json');
    $response->getBody()->write('{"message": "' . \Helper\Generator::generateToken() . '"}');

    return $response;
});