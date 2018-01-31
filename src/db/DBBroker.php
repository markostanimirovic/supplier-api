<?php

namespace Db;

use \PDO;

class DBBroker
{
    private static $instance;
    private $config;

    /**
     * @var $pdo PDO
     */
    private $pdo;

    private function __construct()
    {
        $this->config = require(ROOT . 'src/db/config.php');
    }

    private function connect()
    {
        if (is_null($this->pdo)) {
            try {
                $this->pdo = new PDO("mysql:host={$this->config['host']};dbname={$this->config['schema']};port={$this->config['port']}",
                    $this->config['username'],
                    $this->config['password'],
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ));
            } catch (\PDOException $ex) {
                throw $ex;
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    private function disconnect()
    {
        $this->pdo = null;
    }

    public function startTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->pdo->commit();
    }

    public function rollBack()
    {
        $this->pdo->rollBack();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new DBBroker();
            self::$instance->connect();
        }
        return self::$instance;
    }

    public function insert(array $mapping): bool
    {
        $tableName = $mapping['tableName'];

        $columns = '';
        foreach ($mapping['columns'] as $column) {
            $columns .= $column['name'] . ', ';
        }

        $columns = substr($columns, 0, -2);

        $values = substr(str_repeat('?, ', count($mapping['columns'])), 0, -2);

        $stmt = $this->pdo->prepare("INSERT INTO $tableName ($columns) VALUES ($values)");

        $binded = true;
        $param = 1;
        foreach ($mapping['columns'] as $column) {
            $binded = $stmt->bindParam($param++, $column['value'], $column['type'], !empty($column['size']) ? $column['size'] : null);
            if (!$binded) {
                break;
            }
        }

        try {
            if ($binded && $stmt->execute()) {
                return true;
            } else {
                throw new \Exception("Insert {$tableName}: Problem with bind parameters");
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(array $mapping, int $id): bool
    {
        $tableName = $mapping['tableName'];
        $query = "UPDATE $tableName SET ";

        foreach ($mapping['columns'] as $column) {
            $query .= "{$column['name']} = ?, ";
        }

        $query = substr($query, 0, -2);

        if (!empty($condition)) {
            $query .= ' WHERE id = ' . $id;
        }

        $stmt = $this->pdo->prepare($query);

        $binded = true;
        $param = 1;
        foreach ($mapping['columns'] as $column) {
            $binded = $stmt->bindParam($param++, $column['value'], $column['type'], !empty($column['size']) ? $column['size'] : null);
            if (!$binded) {
                break;
            }
        }

        try {
            if ($binded && $stmt->execute()) {
                return true;
            } else {
                throw new \Exception("Update {$tableName}: Problem with bind parameters");
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function query($query, $loadOne = false): array
    {
        try {
            $stmt = $this->pdo->query($query);

            if ($loadOne) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $result === false ? array() : $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function quote(string $text, int $parameterType = PDO::PARAM_STR)
    {
        return $this->pdo->quote($text, $parameterType);
    }
}