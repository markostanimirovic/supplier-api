<?php

namespace Helper;

class Validator
{
    public static function isProductValid($product, $mapping): bool
    {
        if (is_null($product['name']) || is_array($product['name'])) {
            return false;
        }

        if (strlen($product['name']) > $mapping['columns']['name']['size']) {
            return false;
        }

        return true;
    }
}