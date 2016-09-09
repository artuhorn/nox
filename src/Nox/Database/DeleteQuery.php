<?php

namespace Nox\Database;

/**
 * Class DeleteQuery
 * @package Nox\Database
 */
class DeleteQuery extends Query
{
    /**
     * DeleteQuery constructor
     */
    public function __construct()
    {
        parent::__construct(Query::DELETE);
    }

    /**
     * @param string $tableName
     * @return DeleteQuery $this
     */
    public function deleteFrom(string $tableName)
    {
        $this->addClause('delete', $tableName);
        return $this;
    }

    /**
     * @param string $where
     * @return DeleteQuery $this
     */
    public function where(string $where)
    {
        $this->addClause('where', $where);
        return $this;
    }
}
