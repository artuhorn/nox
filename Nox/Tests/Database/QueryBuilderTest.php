<?php

namespace Nox\Tests\Database;

use Nox\Database\Query;
use Nox\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../autoload.php';

class QueryBuilderTest extends TestCase
{
    public function testSelectQuery()
    {
        $query = QueryBuilder::buildUpon(Query::SELECT)
            ->select('*')
            ->from('tableName')
            ->where('id = :id')
            ->build();

        $this->assertEquals('SELECT * FROM tableName WHERE id = :id', $query);
    }

    public function testInsertQuery()
    {
        $query = QueryBuilder::buildUpon(Query::INSERT)
            ->insertInto('tableName')
            ->columns(['col1', 'col2', 'col3'])
            ->values(['text', '25', 'true'])
            ->build();

        $this->assertEquals('INSERT INTO tableName (col1, col2, col3) VALUES (text, 25, true)', $query);
    }

    public function testUpdateQuery()
    {
        $query = QueryBuilder::buildUpon(Query::UPDATE)
            ->update('tableName')
            ->set(['col1', 'col2'], [':v1', ':v2'])
            ->where('id = :id')
            ->build();

        $this->assertEquals('UPDATE tableName SET col1 = :v1, col2 = :v2 WHERE id = :id', $query);
    }

    public function testDeleteQuery()
    {
        $query = QueryBuilder::buildUpon(Query::DELETE)
            ->deleteFrom('tableName')
            ->where('id = :id')
            ->build();

        $this->assertEquals('DELETE FROM tableName WHERE id = :id', $query);
    }
}
