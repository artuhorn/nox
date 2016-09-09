<?php

namespace Nox\Database;

/**
 * Class UpdateQuery
 * @package Nox\Database
 */
class UpdateQuery extends Query
{
    /**
     * UpdateQuery constructor
     */
    public function __construct()
    {
        parent::__construct(Query::UPDATE);
    }

    /**
     * @param string $tableName
     * @return UpdateQuery $this
     */
    public function update(string $tableName)
    {
        $this->addClause('update', $tableName);
        return $this;
    }

    /**
     * @param array $columns
     * @param array $values
     * @return UpdateQuery $this
     */
    public function set(array $columns, array $values)
    {
        $set = array_map(function ($key, $value) {
            return $key . ' = ' . $value;
        }, $columns, $values);

        $this->addClause('set', implode(', ', $set));
        return $this;
    }

    /**
     * @param string $where
     * @return UpdateQuery $this
     */
    public function where(string $where)
    {
        $this->addClause('where', $where);
        return $this;
    }
}
