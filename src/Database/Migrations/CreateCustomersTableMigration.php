<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use PDO;
use App\Contracts\MigrationInterface;

/**
 * Migration for creating the customers table
 *
 * Handles both creation and removal of the customers table structure.
 */
class CreateCustomersTableMigration implements MigrationInterface
{
    /**
     * Creates the customers table
     *
     * Executes SQL to create a new customers table.
     *
     * @param PDO $pdo Active database connection
     * @return void
     *
     */
    public function up(PDO $pdo): void
    {
        $pdo->exec('
            CREATE TABLE IF NOT EXISTS customers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                address TEXT NOT NULL
            )
        ');
    }

    /**
     * Drops the customers table
     *
     * Reverts the up() operation by removing the customers table.
     *
     * @param PDO $pdo Active database connection
     * @return void
     *
     */
    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS customers');
    }
}
