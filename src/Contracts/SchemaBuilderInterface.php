<?php

declare(strict_types=1);

namespace Src\Contracts;

use PDO;

/**
 * Interface for database schema builders
 *
 * Defines the contract for classes that handle database schema creation and
 * migrations. Implementations should manage table creation, alterations,
 * and other DDL operations.
 */
interface SchemaBuilderInterface
{
    /**
     * Creates or updates the database schema
     *
     * Implementing classes should execute all necessary SQL statements to
     * initialize or update the database structure. This typically includes
     * creating tables, indexes, constraints, and other database objects.
     *
     * @param PDO $pdo An active PDO database connection
     * @return void
     */
    public function createSchema(PDO $pdo): void;
}
