<?php

namespace Nox\Database;

/**
 * Class InsertQuery
 * @package Nox\Database
 */
class InsertQuery extends Query
{
    /**
     * InsertQuery constructor
     */
    public function __construct()
    {
        parent::__construct(Query::INSERT);
    }

    /**
     * @param string $tableName
     * @return InsertQuery $this
     */
    public function insertInto(string $tableName)
    {
        $this->addClause('insert', $tableName);
        return $this;
    }

    /**
     * @param array $columns
     * @return InsertQuery
     */
    public function columns(array $columns)
    {
        $this->addClause('columns', implode(', ', $columns));
        return $this;
    }
    
    /**
     * @param array $values
     * @return InsertQuery $this
     */
    public function values(array $values)
    {
        $this->addClause('values', implode(', ', $values));
        return $this;
    }
}
