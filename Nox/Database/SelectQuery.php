<?php

namespace Nox\Database;

/**
 * Class SelectQuery
 * @package Nox\Database
 */
class SelectQuery extends Query
{
    /**
     * SelectQuery constructor
     */
    public function __construct()
    {
        parent::__construct(Query::SELECT);
    }
    
    /**
     * @param string $columns
     * @return SelectQuery $this
     */
    public function select(string $columns)
    {
        $this->addClause('select', $columns);
        return $this;
    }

    /**
     * @param string $tableName
     * @return SelectQuery $this
     */
    public function from(string $tableName)
    {
        $this->addClause('from', $tableName);
        return $this;
    }

    /**
     * @param string $where
     * @return SelectQuery $this
     */
    public function where(string $where)
    {
        $this->addClause('where', $where);
        return $this;
    }

    /**
     * @param string $column
     * @return SelectQuery $this
     */
    public function groupBy(string $column)
    {
        $this->addClause('groupBy', $column);
        return $this;
    }

    /**
     * @param string $having
     * @return SelectQuery $this
     */
    public function having(string $having)
    {
        $this->addClause('having', $having);
        return $this;
    }

    /**
     * @param string $column
     * @return SelectQuery $this
     */
    public function orderBy(string $column)
    {
        $this->addClause('orderBy', $column);
        return $this;
    }

    /**
     * @param int $limit
     * @return SelectQuery $this
     */
    public function limit(int $limit)
    {
        $this->addClause('limit', $limit);
        return $this;
    }

    /**
     * @param int $offset
     * @return SelectQuery $this
     */
    public function offset(int $offset)
    {
        $this->addClause('offset', $offset);
        return $this;
    }

    /**
     * @param array $clauses
     * @return SelectQuery $this
     */
    public function fill(array $clauses)
    {
        foreach ($clauses as $clause => $value) {
            $this->addClause($clause, $value);
        }
        return $this;
    }
}
