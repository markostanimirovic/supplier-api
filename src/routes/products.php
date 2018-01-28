<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Db\DBBroker;
use \Helper\Validator;

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

$app->post('/products[/]', function (Request $request, Response $response) {
    $response = $response->withHeader('Content-type', 'application/json');

    $product = json_decode($request->getBody(), true);
    $mapping = require '../src/db/mapping/product.php';

    if (!empty($product) && Validator::isProductValid($product, $mapping)) {
        try {
            $db = DBBroker::getInstance();

            $mapping['columns']['name']['value'] = $product['name'];
            $db->insert($mapping);
            $response->getBody()->write('{"message": "The product is successfully inserted!"}');
        } catch (\Exception $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write('{"message": "Internal Server Error"}');
        }
    } else {
        $response = $response->withStatus(400);
        $response->getBody()->write('{"message" : "Bad Request"}');
    }
    return $response;
});