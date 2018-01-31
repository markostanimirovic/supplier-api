<?php

namespace Db\Dao;


interface ProductDao
{
    public function getAll(): array;

    public function getById(int $id): array;

    public function getByCode(string $code, int $id = 0): array;

    public function insert(array $product): bool;

    public function update(int $id, array $data): bool;
}