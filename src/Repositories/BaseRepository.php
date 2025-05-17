<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;
use PDOStatement;

/**
 * Provides common database operation methods for all repositories.
 */
abstract class BaseRepository
{
    /**
     * The PDO database connection.
     *
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * BaseRepository constructor.
     *
     * @param PDO $pdo The PDO connection instance.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Execute a SQL statement with optional parameters.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params Parameters to bind in the query.
     * @return PDOStatement The prepared and executed statement.
     *
     * @throws \RuntimeException If an error occurs during execution.
     */
    protected function execute(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\Throwable $e) {
            throw new \RuntimeException("Database execution failed: " . $e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * Insert a new record and return the last insert ID.
     *
     * @param string $sql The insert SQL query.
     * @param array $params Parameters to bind in the insert query.
     * @return int The ID of the inserted record.
     *
     * @throws RuntimeException If an error occurs during insertion.
     */
    protected function insert(string $sql, array $params = []): int|bool
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return (int) $this->pdo->lastInsertId();
        } catch (\Throwable $e) {
            throw new \RuntimeException("Insert operation failed: " . $e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
