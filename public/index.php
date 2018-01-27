<?php
require '../vendor/autoload.php';

$app = new \Slim\App;

require_once('../src/routes/products.php');

$app->run();