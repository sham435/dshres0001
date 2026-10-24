<?php
declare(strict_types=1);

namespace Core;

use Core\Database;

/**
 * Base Model
 * 
 * Provides common CRUD operations for all models.
 * Extend this class for specific entity models.
 */
abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $hidden = [];

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function all(array $conditions = [], string $orderBy = 'created_at DESC', int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= " ORDER BY {$orderBy}";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::fetchAll($sql, $params);
    }

    public function find(string $id): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id",
            ['id' => $id]
        );
    }

    public function findBy(string $column, mixed $value): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM {$this->table} WHERE {$column} = :value",
            ['value' => $value]
        );
    }

    public function create(array $data): string
    {
        $filtered = $this->filterFillable($data);
        return Database::insert($this->table, $filtered);
    }

    public function update(string $id, array $data): int
    {
        $filtered = $this->filterFillable($data);
        return Database::update(
            $this->table,
            $filtered,
            "{$this->primaryKey} = :id",
            ['id' => $id]
        );
    }

    public function delete(string $id): int
    {
        return Database::delete(
            $this->table,
            "{$this->primaryKey} = :id",
            ['id' => $id]
        );
    }

    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        return (int) Database::fetchColumn($sql, $params);
    }

    public function exists(string $column, mixed $value, ?string $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE {$column} = :value";
        $params = ['value' => $value];

        if ($excludeId !== null) {
            $sql .= " AND {$this->primaryKey} != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        return (int) Database::fetchColumn($sql, $params) > 0;
    }

    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function hideFields(array $data): array
    {
        foreach ($this->hidden as $field) {
            unset($data[$field]);
        }
        return $data;
    }
}