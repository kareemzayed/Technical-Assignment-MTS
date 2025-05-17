<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

/**
 * Factory class for creating PDO database connections
 * 
 * Provides a standardized way to instantiate PDO connections with
 * consistent configuration options. Primarily used for connection
 * pooling and centralized PDO instance creation.
 */
class PdoFactory
{
    /**
     * Creates a new PDO database connection instance
     *
     * @param string $dsn The Data Source Name (connection string)
     * @param array $options Optional PDO driver-specific connection options
     * 
     * @return PDO A configured PDO database connection instance
     * 
     */
    public function create(string $dsn, array $options = []): PDO
    {
        return new PDO($dsn, null, null, $options);
    }
}
