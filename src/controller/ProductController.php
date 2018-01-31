<?php

namespace Controller;

use Helper\Validator;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Db\DBBroker;

class ProductController
{

    public function getAll(Request $request, Response $response)
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $mapping = require ROOT . '/src/db/mapping/product.php';

        try {
            $db = DBBroker::getInstance();
            $products = $db->query("SELECT * FROM {$mapping['tableName']}");
            $response->getBody()->write(json_encode($products, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write('{"message": "Internal Server Error"}');
        }
        return $response;
    }

    public function insert(Request $request, Response $response)
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $product = json_decode($request->getBody(), true);
        $mapping = require(ROOT . '/src/db/mapping/product.php');

        if (!empty($product) && Validator::isProductValid($product, $mapping)) {
            try {
                $db = DBBroker::getInstance();

                $mapping['columns']['code']['value'] = $product['code'];
                $mapping['columns']['name']['value'] = $product['name'];
                $mapping['columns']['unit']['value'] = $product['unit'];
                $mapping['columns']['price']['value'] = $product['price'];

                $quotedCode = $db->quote($product['code']);
                if (!empty($db->query("SELECT id FROM {$mapping['tableName']} WHERE {$mapping['columns']['code']['name']} = {$quotedCode}"))) {
                    $response = $response->withStatus(409);
                    $response->getBody()->write('{"message": "The product already exists!"}');
                    return $response;
                }
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
    }

    public function update(Request $request, Response $response, array $args)
    {
        //@TODO: implement update
        return $args['id'];
    }
}