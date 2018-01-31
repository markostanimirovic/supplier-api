<?php

namespace Db\Dao\Impl;

use Db\Dao\ProductDao;
use Db\DBBroker;

class ProductDaoImpl implements ProductDao
{
    protected $db;
    protected $mapping;

    function __construct()
    {
        try {
            $this->db = DBBroker::getInstance();
            $this->mapping = require(ROOT . '/src/db/mapping/product.php');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getAll(): array
    {
        try {
            $query = "SELECT * FROM {$this->mapping['tableName']}";
            $products = $this->db->query($query);
            return $products;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getById(int $id): array
    {
        try {
            $query = "SELECT * FROM {$this->mapping['tableName']} WHERE id = {$id}";
            $product = $this->db->query($query, true);
            return $product;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getByCode(string $code, int $id = 0): array
    {
        try {
            $quotedCode = $this->db->quote($code);
            $query = "SELECT * FROM {$this->mapping['tableName']} WHERE {$this->mapping['columns']['code']['name']} = {$quotedCode}";

            if ($id !== 0) {
                $query .= " AND id != {$id}";
            }

            $product = $this->db->query($query, true);
            return $product;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function insert(array $product): bool
    {
        $this->mapping['columns']['code']['value'] = $product['code'];
        $this->mapping['columns']['name']['value'] = $product['name'];
        $this->mapping['columns']['unit']['value'] = $product['unit'];
        $this->mapping['columns']['price']['value'] = $product['price'];

        try {
            return $this->db->insert($this->mapping);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(int $id, array $product): bool
    {
        $this->mapping['columns']['code']['value'] = $product['code'];
        $this->mapping['columns']['name']['value'] = $product['name'];
        $this->mapping['columns']['unit']['value'] = $product['unit'];
        $this->mapping['columns']['price']['value'] = $product['price'];

        try {
            return $this->db->update($id, $this->mapping);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}