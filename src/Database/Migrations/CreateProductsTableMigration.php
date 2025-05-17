<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use PDO;
use App\Contracts\MigrationInterface;

/**
 * Migration for creating the products table
 */
class CreateProductsTableMigration implements MigrationInterface
{
    /**
     * Creates the products table.
     *
     * @param PDO $pdo Active database connection
     * @return void
     */
    public function up(PDO $pdo): void
    {
        $pdo->exec('
            CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                price DECIMAL(8, 2) NOT NULL
            )
        ');
    }

    /**
     * Drops the products table
     *
     * Safely removes the table if it exists.
     * @param PDO $pdo Active database connection
     * @return void
     */
    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS products');
    }
}
