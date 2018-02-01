<?php

namespace Helper;

class Validator
{
    public static function isProductValid($product): bool
    {
        $mapping = require(ROOT . '/src/db/mapping/product.php');

        //code validation
        if (is_null($product['code'] || is_array($product['code']))) {
            return false;
        }
        if (strlen($product['code']) > $mapping['columns']['code']['size'] || strlen($product['code']) == 0) {
            return false;
        }

        //name validation
        if (is_null($product['name']) || is_array($product['name'])) {
            return false;
        }
        if (strlen($product['name']) > $mapping['columns']['name']['size'] || strlen($product['name']) == 0) {
            return false;
        }

        //unit validation
        if (is_null($product['unit'] || is_array($product['unit']))) {
            return false;
        }
        if (strlen($product['unit']) > $mapping['columns']['unit']['size'] || strlen($product['unit']) == 0) {
            return false;
        }
        if (!in_array($product['unit'], ['komad', 'gram', 'kilogram', 'mililitar', 'litar'])) {
            return false;
        }

        //price validation
        if (is_null($product['price'] || is_array($product['price']))) {
            return false;
        }
        if (strlen($product['price']) > $mapping['columns']['price']['size'] || strlen($product['price']) == 0) {
            return false;
        }
        if (!floatval($product['price'])) {
            return false;
        }

        return true;
    }

    public static function isIdValid($id): bool
    {
        if (strlen((string)$id) != 0 && ctype_digit((string)$id)) {
            return true;
        }
        return false;
    }

    public static function isUserValid($user): bool
    {
        $mapping = require(ROOT . '/src/db/mapping/user.php');

        //username validation
        if (is_null($user['username'] || is_array($user['username']))) {
            return false;
        }
        if (strlen($user['username']) > $mapping['columns']['username']['size'] || strlen($user['username']) == 0) {
            return false;
        }

        //password validation
        if (is_null($user['password'] || is_array($user['password']))) {
            return false;
        }
        if (strlen($user['password']) > $mapping['columns']['password']['size'] || strlen($user['password']) == 0) {
            return false;
        }
        
        return true;
    }
}