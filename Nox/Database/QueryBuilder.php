<?php

namespace Nox\Database;

/**
 * Class QueryBuilder
 * @package Nox\Database
 */
class QueryBuilder
{
    protected function __construct()
    {
    }

    /**
     * В зависимости от типа запроса возвращает тот или ной класс для
     * работы с данными запросом
     *
     * @param int $queryType
     * @return DeleteQuery|InsertQuery|SelectQuery|UpdateQuery
     * @throws \Exception
     */
    public static function buildUpon(int $queryType)
    {
        switch ($queryType) {
            case Query::SELECT:
                return new SelectQuery();
            case Query::INSERT:
                return new InsertQuery();
            case Query::UPDATE:
                return new UpdateQuery();
            case Query::DELETE:
                return new DeleteQuery();
            default:
                throw new \Exception('Invalid Query Type');
        }
    }
}
