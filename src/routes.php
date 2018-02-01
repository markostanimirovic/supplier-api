<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/token[/]', 'AuthController:getToken');

$app->get('/products[/]', 'ProductController:getAll');

$app->get('/products/{id}[/]', 'ProductController:getById');

$app->post('/products[/]', 'ProductController:insert');

$app->put('/products/{id}[/]', 'ProductController:update');