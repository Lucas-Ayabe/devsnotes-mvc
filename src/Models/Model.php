<?php
namespace App\Models;

use PDOStatement;

use function App\functions\helpers\to_snake_case;

/**
 * The base Model class.
 */
abstract class Model
{
    protected \PDO $connection;
    protected string $tablePrefix = "tb_";
    protected string $table = "";
    protected string $tablePosfix = "s";

    /**
     * @var int the primary key of the table.
     */
    protected int $id = 0;

    public function __construct()
    {
        if (empty($this->table)) {
            $this->table = $this->getTableName();
        }
    }

    public function setConnection(\PDO $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    public static function createFromConnection(\PDO $connection): self
    {
        return (new static())->setConnection($connection);
    }

    protected function getTableName(): string
    {
        $className = explode('\\', get_called_class());
        return $this->tablePrefix .
            to_snake_case(end($className)) .
            $this->tablePosfix;
    }

    protected function getPreparedFields(array $fieldsArray)
    {
        $columns = implode(",", array_keys($fieldsArray));
        $values = implode(",", array_map(fn() => "?", $fieldsArray));

        return [$columns, $values, "columns" => $columns, "values" => $values];
    }

    /**
     * Get the value of table
     */
    public function getTable(): string
    {
        return $this->table;
    }

    protected function selectAll(array $columns = ["*"]): PDOStatement
    {
        $selectedColumns = implode(",", $columns);
        $statement = $this->connection->prepare(
            "SELECT $selectedColumns FROM {$this->table}"
        );

        $statement->execute();

        return $statement;
    }

    protected function selectById(int $id, array $columns = ['*'])
    {
        $selectedColumns = implode(",", $columns);
        $statement = $this->connection->prepare(
            "SELECT $selectedColumns FROM {$this->table} WHERE id = $id LIMIT 1"
        );
        $statement->execute();

        return $statement;
    }

    /**
     *
     *
     * @param array $columns
     * @return static[]
     */
    public function findAll(array $columns = ["*"]): array
    {
        $statement = $this->selectAll($columns);

        return $statement->fetchAll(\PDO::FETCH_CLASS, get_called_class());
    }

    public function findAllArray(array $columns = ["*"]): array
    {
        $statement = $this->selectAll($columns);
        return $statement->fetchAll();
    }

    /**
     *
     *
     * @param integer $id
     * @param array $columns
     * @return self
     */
    public function findById(int $id, array $columns = ['*']): self
    {
        $statement = $this->selectById($id, $columns);
        return $statement->fetchObject(get_called_class());
    }

    public function findByIdArray(int $id, array $columns = ['*']): array
    {
        $statement = $this->selectById($id, $columns);
        return $statement->fetch();
    }

    public function saveWith(array $fields): bool
    {
        list($columns, $values) = $this->getPreparedFields($fields);

        $statement = $this->connection->prepare(
            "INSERT INTO {$this->table}($columns) VALUES($values)"
        );
        $statement->execute(array_values($fields));

        return (bool) $statement->rowCount();
    }

    public function updateWith(array $fields): bool
    {
        $sets = implode(
            ",",
            array_map(function ($column) {
                return "$column = ?";
            }, array_keys($fields))
        );

        $statement = $this->connection->prepare(
            "UPDATE {$this->table} SET $sets WHERE id = {$this->id}"
        );
        $statement->execute(array_values($fields));

        return (bool) $statement->rowCount();
    }

    public function destroy(): bool
    {
        $statement = $this->connection->prepare(
            "DELETE FROM {$this->table} WHERE id = {$this->id}"
        );
        $statement->execute();

        return (bool) $statement->rowCount();
    }

    /**
     * Inserts a new Model in the Database.
     *
     * @return boolean
     */
    abstract public function save(): bool;

    /**
     * Updates a existent Model in the Database.
     *
     * @return boolean
     */
    abstract public function update(): bool;
}
