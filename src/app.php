<?php

require ROOT . '/vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$container = $app->getContainer();

$container['ProductController'] = function () {
    return new Controller\ProductController;
};

$container['AuthController'] = function () {
    return new Controller\AuthController;
};

$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => "supplier-api",
    "rules" => [
        new \Slim\Middleware\JwtAuthentication\RequestMethodPathRule([
            "path" => ["/products"],
            "passthrough" => ["GET" => "/products", "OPTIONS" => "/products"]
        ])
    ],
    "secure" => false,
    "error" => function ($request, $response, $arguments) {
        $data["message"] = $arguments["message"];
        return $response->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    },
    "algorithm" => ["HS256"]
]));

require ROOT . '/src/routes.php';