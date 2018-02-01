<?php

namespace Db\Dao\Impl;

use Db\Dao\UserDao;
use Db\DBBroker;

class UserDaoImpl implements UserDao
{
    protected $db;
    protected $mapping;

    function __construct()
    {
        try {
            $this->db = DBBroker::getInstance();
            $this->mapping = require(ROOT . '/src/db/mapping/user.php');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getByUsernameAndPassword(string $username, string $password): array
    {
        try {
            $quotedUsername = $this->db->quote($username);
            $quotedPassword = $this->db->quote($password);

            $query = "SELECT * FROM {$this->mapping['tableName']} WHERE {$this->mapping['columns']['username']['name']} = {$quotedUsername}";
            $query .= " AND {$this->mapping['columns']['password']['name']} = {$quotedPassword}";

            $user = $this->db->query($query, true);
            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}