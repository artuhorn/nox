<?php

namespace Nox\Database;

use Nox\Core\Application;
use Nox\Exceptions\DatabaseException;
use Nox\Helpers\TSingleton;
use PDO;

/**
 * Class Database
 * @package Nox\Database
 */
class Database
{
    use TSingleton;

    /** @var string */
    public $lastId = null;

    /** @var PDO */
    protected $dbh;

    protected function __construct()
    {
        $config = Application::instance()->config;
        $dsn = 'mysql:dbname=' . $config->db->dbname .
            ';host=' . $config->db->host . ':' . $config->db->port .
            ';charset=UTF8';

        try {
            $dbh = new PDO($dsn, $config->db->login, $config->db->password);
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }

        $this->dbh = $dbh;
    }

    /**
     * @param string $query
     * @param array $params
     * @throws DatabaseException
     * @return bool
     */
    public function execute(string $query, array $params = []): bool
    {
        try {
            $sth = $this->dbh->prepare($query);
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
        $result = $sth->execute($params);
        $this->lastId = $this->dbh->lastInsertId();

        return $result;
    }

    /**
     * @param string $query
     * @param array $params
     * @param string $class
     * @throws DatabaseException
     * @return array
     */
    public function query(string $query, array $params = [], string $class = 'stdClass'): array
    {
        try {
            $sth = $this->dbh->prepare($query);
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
        $success = $sth->execute($params);
        $this->lastId = $this->dbh->lastInsertId();

        if (!$success) {
            return [];
        }

        return $sth->fetchAll(PDO::FETCH_CLASS, $class);
    }

    public function beginTransaction()
    {
        $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        $this->dbh->commit();
    }

//    public function queryEach(string $query, array $params = [], string $class = 'stdClass')
//    {
//        try {
//            $sth = $this->dbh->prepare($query);
//        } catch (\PDOException $e) {
//            throw new DatabaseException($e->getMessage());
//        }
//        $success = $sth->execute($params);
//        $this->lastId = $this->dbh->lastInsertId();
//
//        if (!$success) {
//            yield;
//        }
//
//        while ($row = $sth->fetchObject($class)) {
//            yield $row;
//        }
//    }
}
