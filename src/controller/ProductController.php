<?php

namespace Controller;

use Db\Dao\Impl\ProductDaoImpl;
use Helper\Validator;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class ProductController
{
    public function getAll(Request $request, Response $response)
    {
        if (!empty($request->getQueryParams()['code'])) {
            return $this->getByCode($request, $response, $request->getQueryParams()['code']);
        }

        $response = $response->withHeader('Content-type', 'application/json');
        try {
            $productDao = new ProductDaoImpl();
            $products = $productDao->getAll();
            $response->getBody()->write(json_encode($products, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write('{"message": "Internal Server Error"}');
        }
        return $response;
    }

    public function getById(Request $request, Response $response)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $id = $request->getAttribute('id');
        if (!Validator::isIdValid($id)) {
            $response = $response->withStatus(400);
            $response->getBody()->write('{"message": "Bad Request"}');
            return $response;
        }

        $id = (int)$id;

        try {
            $productDao = new ProductDaoImpl();
            $product = $productDao->getById($id);

            if (empty($product)) {
                $response = $response->withStatus(404);
                $response->getBody()->write('{"message": "Not Found"}');
                return $response;
            }

            $response->getBody()->write(json_encode($product, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write('{"message": "Internal Server Error"}');
        }
        return $response;
    }

    private function getByCode(Request $request, Response $response, string $code)
    {
        $response = $response->withHeader('Content-type', 'application/json');

        try {
            $productDao = new ProductDaoImpl();
            $product = $productDao->getByCode($code);

            if (empty($product)) {
                $response = $response->withStatus(404);
                $response->getBody()->write('{"message": "Not Found"}');
                return $response;
            }

            $response->getBody()->write(json_encode($product, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write('{"message": "Internal Server Error"}');
        }
        return $response;
    }

    public function insert(Request $request, Response $response)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response = $response->withStatus(201);
        $product = json_decode($request->getBody(), true, $options = JSON_UNESCAPED_UNICODE);

        if (!empty($product) && Validator::isProductValid($product)) {
            try {
                $productDao = new ProductDaoImpl();
                if (!empty($productDao->getByCode($product['code']))) {
                    $response = $response->withStatus(409);
                    $response->getBody()->write('{"message": "The product already exists!"}');
                    return $response;
                }
                $productDao->insert($product);
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

    public function update(Request $request, Response $response)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $product = json_decode($request->getBody(), true, $options = JSON_UNESCAPED_UNICODE);
        $id = $request->getAttribute('id');

        if (!empty($product) && Validator::isProductValid($product) && Validator::isIdValid($id)) {
            try {
                $productDao = new ProductDaoImpl();
                $id = (int)$id;

                if (empty($productDao->getById($id))) {
                    $response = $response->withStatus(404);
                    $response->getBody()->write('{"message": "Not Found"}');
                    return $response;
                }

                if (!empty($productDao->getByCode($product['code'], $id))) {
                    $response = $response->withStatus(409);
                    $response->getBody()->write('{"message": "The product already exists!"}');
                    return $response;
                }

                $productDao->update($id, $product);
                $response->getBody()->write('{"message": "The product is successfully updated!"}');
            } catch (\Exception $e) {
                $response = $response->withStatus(500);
                $response->getBody()->write('{"message": "Internal Server Error . ' . $e->getMessage() . '"}');
            }
        } else {
            $response = $response->withStatus(400);
            $response->getBody()->write('{"message" : "Bad Request"}');
        }
        return $response;
    }
}