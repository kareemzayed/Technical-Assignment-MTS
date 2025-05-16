<?php

declare(strict_types=1);

namespace Src\Repositories;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Abstract base repository providing common database operations
 *
 * Implements core CRUD operations and error handling to be extended by
 * concrete repository classes. Serves as an abstraction layer between
 * business logic and database operations.
 */
abstract class BaseRepository
{
    /** @var PDO Active database connection */
    protected PDO $pdo;

    /**
     * Initialize repository with database connection
     *
     * @param PDO $pdo Active PDO database connection
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Execute a parameterized SQL query
     *
     * @param string $sql The SQL query with parameter placeholders
     * @param array $params Associative array of parameters [':param' => value]
     * @return PDOStatement|false Prepared statement on success, false on failure
     */
    protected function execute(string $sql, array $params = []): PDOStatement|false
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Execute an INSERT statement and return last insert ID
     *
     * @param string $sql INSERT SQL statement
     * @param array $params Associative array of parameters
     * @return int|false Insert ID on success, false on failure
     */
    protected function insert(string $sql, array $params = []): int|bool
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}
