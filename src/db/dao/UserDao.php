<?php

namespace Db\Dao;


interface UserDao
{
    public function getByUsernameAndPassword(string$username, string $password): array;
}