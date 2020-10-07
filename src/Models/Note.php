<?php
namespace App\Models;

use PDO;

/**
 * The Note Model class.
 */
class Note extends Model
{
    protected string $tablePrefix = "";
    private string $title = '';
    private string $body = '';

    /**
     * Redundant reimplementation for fix intellisense return type.
     *
     * @param PDO $connection
     * @return Note
     */
    public static function createFromConnection(PDO $connection): Note
    {
        return parent::createFromConnection($connection);
    }

    /**
     * Redundant reimplementation for fix intellisense return type.
     *
     * @param integer $id
     * @param array $columns
     * @return self
     */
    public function findById(int $id, array $columns = ["*"]): self
    {
        return parent::findById($id, $columns);
    }

    /**
     * Redundant reimplementation for fix intellisense return type.
     *
     * @param \PDO $connection
     * @return self
     */
    public function setConnection(\PDO $connection): self
    {
        return parent::setConnection($connection);
    }

    public function save(): bool
    {
        return parent::saveWith([
            "title" => $this->title,
            "body" => $this->body,
        ]);
    }

    public function update(): bool
    {
        return parent::updateWith([
            "title" => $this->title,
            "body" => $this->body,
        ]);
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of body
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Set the value of body
     *
     * @return  self
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}
