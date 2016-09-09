<?php

namespace Nox\Database;

/**
 * Class Query
 * @package Nox\Database
 */
abstract class Query
{
    const SELECT = 1;
    const INSERT = 2;
    const UPDATE = 3;
    const DELETE = 4;

    /**
     * В массиве хранятся значения вида ['key' => 'value'], где ключ -
     * одно из ключевых слов запроса (например, ['select' => '*'])
     *
     * @var array[]
     */
    protected $clauses = [];

    /** @var int */
    protected $queryType = 0;

    /**
     * Массив задает порядок сборки конечного запроса. В ключах содержатся
     * части синтаксиса конкретного типа запроса, а в значениях - ключи
     * из массива $clauses
     *
     * @var array
     */
    protected $buildOrder = [];

    /**
     * QueryParams constructor
     * @param int $queryType
     */
    public function __construct(int $queryType)
    {
        $this->queryType = $queryType;

        $this->fillBuildOrder($queryType);
    }

    /**
     * Выполяет заполнение массива buildOrder согласно типу переданного запроса
     *
     * @param int $queryType
     */
    protected function fillBuildOrder(int $queryType)
    {
        switch ($queryType) {
            case Query::SELECT:
                $this->buildOrder = [
                    'SELECT' => 'select',
                    'FROM' => 'from',
                    'WHERE' => 'where',
                    'GROUP BY' => 'groupBy',
                    'HAVING' => 'having',
                    'ORDER BY' => 'orderBy',
                    'LIMIT' => 'limit',
                    'OFFSET' => 'offset'
                ];
                break;

            case Query::INSERT:
                $this->buildOrder = [
                    'INSERT INTO' => 'insert',
                    '()' => 'columns',
                    'VALUES ()' => 'values'
                ];
                break;

            case Query::UPDATE:
                $this->buildOrder = [
                    'UPDATE' => 'update',
                    'SET' => 'set',
                    'WHERE' => 'where'
                ];
                break;

            case Query::DELETE:
                $this->buildOrder = [
                    'DELETE FROM' => 'delete',
                    'WHERE' => 'where'
                ];
                break;
        }

    }

    /**
     * Добавляет в массив условий новое условие
     *
     * @param string $clause
     * @param string $value
     */
    public function addClause(string $clause, string $value)
    {
        $this->clauses[$clause] = $value;
    }

    /**
     * Выполняет построение запроса
     *
     * @return string
     */
    public function build(): string
    {
        $query = '';

        foreach ($this->buildOrder as $statement => $clause) {
            $clauseString = $this->clauses[$clause] ?? '';
            if ($clauseString == '') {
                continue;
            }

            if (strpos($statement, '()') !== false) {
                $query .= ' ' . preg_replace('~\(\)~', '(' . $clauseString . ')', $statement);
            } else {
                $query .= ' ' . $statement . ' ' . $clauseString;
            }
        }

        return trim($query);
    }
}
