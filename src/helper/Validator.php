<?php

namespace Helper;

class Validator
{
    public static function isProductValid($product, $mapping): bool
    {
        //code validation
        if (is_null($product['code'] || is_array($product['code']))) {
            return false;
        }

        if (strlen($product['code']) > $mapping['columns']['code']['size']) {
            return false;
        }

        //name validation
        if (is_null($product['name']) || is_array($product['name'])) {
            return false;
        }

        if (strlen($product['name']) > $mapping['columns']['name']['size']) {
            return false;
        }

        //unit validation
        if (is_null($product['unit'] || is_array($product['unit']))) {
            return false;
        }

        if (strlen($product['unit']) > $mapping['columns']['unit']['size']) {
            return false;
        }

        if (!in_array($product['unit'], ['komad', 'gram', 'kilogram', 'mililitar', 'litar'])) {
            return false;
        }

        //price validation
        if (is_null($product['price'] || is_array($product['price']))) {
            return false;
        }

        if (strlen($product['price']) > $mapping['columns']['price']['size']) {
            return false;
        }

        if (!floatval($product['price'])) {
            return false;
        }

        return true;
    }
}