<?php

declare(strict_types=1);

namespace App\Contracts;

use PDO;


/**
 * Contract for database migration operations
 * 
 * Defines the standard structure for reversible database migrations.
 * Implementations should handle both application and rollback of schema changes.
 */
interface MigrationInterface
{
    /**
     * Applies the migration
     * 
     * Executes all necessary statements to insert required data.
     *
     * @param PDO $pdo Active database connection
     * @return void
     * 
     */
    public function up(PDO $pdo): void;

    /**
     * Reverts the migration
     * 
     * Rolls back all changes made by the up() method.
     *
     * @param PDO $pdo Active database connection
     * @return void
     * 
     */
    public function down(PDO $pdo): void;
}
