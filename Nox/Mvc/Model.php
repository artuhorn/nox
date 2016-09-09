<?php

namespace Nox\Orm;

use Nox\Core\Base;
use Nox\Database\Database;
use Nox\Database\Query;
use Nox\Database\QueryBuilder;
use Nox\Helpers\StringHelper;

/**
 * Class Model
 * @package Nox\Orm
 */
abstract class Model extends Base
{
    protected $data = [];

    /** @var bool */
    protected $deleted = false;

    /** @var bool */
    protected $modified = false;

    /**
     * @return static[]
     */
    public static function findAll(): array
    {
        return static::find();
    }

    /**
     * @param array $findClause
     * @param array $params
     * @param string $tableName
     * @return array|static[]
     */
    public static function find(array $findClause = null, array $params = [], string $tableName = null): array
    {
        $query = QueryBuilder::buildUpon(Query::SELECT)
            ->select('*')
            ->from($tableName ?: static::getTableName())
            ->fill($findClause)
            ->build();

        return Database::instance()->query($query, $params, static::class);
    }

    /**
     * Возвращает имя таблицы, в которой хранятся данные модели,
     * основываясь на имени класса
     * \App\Models\SomeModel -> somemodel
     *
     * @return string
     */
    public static function getTableName()
    {
        $className = StringHelper::getBaseClassName(static::class);
        return strtolower($className);
    }

    /**
     * @param int $id
     * @return null|static
     */
    public static function findById(int $id)
    {
        if (empty($id)) {
            return null;
        }

        $result = static::find(['where' => 'id = :id'], [':id' => $id]);

        if (count($result) > 0) {
            return $result[0];
        }

        return null;
    }

    public function __get($key)
    {
        return $this->data[$key];
    }

    public function __set($key, $value)
    {
        $this->modified = true;
        $this->data[$key] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        if ($this->isDeleted()) {
            return false;
        }

        if ($this->isNew()) {
            return false;
        }

        return $this->deleteRecord();
    }

    /**
     * @return bool
     */
    final protected function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return empty($this->getId());
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    protected function deleteRecord(): bool
    {
        $query = QueryBuilder::buildUpon(Query::DELETE)
            ->deleteFrom(static::getTableName())
            ->where('id = :id')
            ->build();

        $success = Database::instance()->execute($query, [':id' => $this->getId()]);
        if ($success === true) {
            $this->deleted = true;
        }
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->modified) {
            return true;
        }

        $this->validate();

        return $this->saveRecord();
    }

    /**
     * Validates model fields
     *
     * @throws array|\Nox\Exceptions\MultiException[]
     * @return void
     */
    abstract protected function validate();

    /**
     * @return bool
     */
    protected function saveRecord(): bool
    {
        if ($this->isNew()) {
            $success = $this->insert();
        } else {
            $success = $this->update();
        }
        if ($success === true) {
            $this->modified = false;
        }

        return $success;
    }

    /**
     * @return bool
     */
    protected function insert(): bool
    {
        $fields = [];
        $params = [];
        $this->setFieldsAndParams($fields, $params);

        $query = QueryBuilder::buildUpon(Query::INSERT)
            ->insertInto(static::getTableName())
            ->columns(array_keys($fields))
            ->values($fields)
            ->build();

        $db = Database::instance();
        $result = $db->execute($query, $params);
        if ($result === true) {
            $this->id = $db->lastId;
        }

        return $result;
    }

    /**
     * @param array $fields
     * @param array $params
     */
    protected function setFieldsAndParams(array &$fields, array &$params)
    {
        foreach ($this->data as $field => $value) {
            if ($field == 'id') {
                continue;
            }
            $fields[$field] = ':' . $field;
            $params[':' . $field] = $value;
        }
    }

    /**
     * @return bool
     */
    protected function update(): bool
    {
        $fields = [];
        $params = [];
        $this->setFieldsAndParams($fields, $params);

        $query = QueryBuilder::buildUpon(Query::UPDATE)
            ->update(static::getTableName())
            ->set(array_keys($fields), $fields)
            ->where('id = :id')
            ->build();

        $db = Database::instance();
        return $db->execute($query, array_merge($params, [':id' => $this->getId()]));
    }

    /**
     * @return bool
     */
    final protected function isModified(): bool
    {
        return $this->modified;
    }
}
