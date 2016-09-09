<?php

namespace Nox\Database;

abstract class Migration
{
    public function __construct()
    {
    }


    abstract protected function up();

    abstract protected function down();

    final protected function createTable(string $tableName, array $columns)
    {

    }

    final protected function dropTable(string $tableName)
    {

    }
}
