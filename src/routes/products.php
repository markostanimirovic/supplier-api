<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Db\DBBroker;

$app->get('/products[/]', function (Request $request, Response $response) {
    $response = $response->withHeader('Content-type', 'application/json');

    try {
        $db = DBBroker::getInstance();
        $products = $db->query("SELECT * FROM products");
        $response->getBody()->write(json_encode($products, JSON_UNESCAPED_UNICODE));
    } catch (\Exception $e) {
        $response = $response->withStatus(500);
        $response->getBody()->write('{"message": "Internal Server Error"}');
    }
    return $response;
});