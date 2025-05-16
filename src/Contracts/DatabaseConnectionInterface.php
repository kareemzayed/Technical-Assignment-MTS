<?php

declare(strict_types=1);

namespace Src\Contracts;

use PDO;

/**
 * Interface for database connection implementations
 * 
 * Ensures consistent database connection handling across different implementations
 * by defining a contract that all connection classes must follow.
 */
interface DatabaseConnectionInterface
{
    /**
     * Gets a PDO database connection instance
     * 
     * Implementing classes must return an initialized PDO connection object
     * that's ready for database operations. The implementation should handle
     * connection establishment and any required configuration.
     * 
     * @return PDO An active PDO database connection
     */
    public function getConnection(): PDO;
}
