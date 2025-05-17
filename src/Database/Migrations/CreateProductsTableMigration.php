<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use PDO;
use App\Contracts\MigrationInterface;

class CreateProductsTableMigration implements MigrationInterface
{
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

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS products');
    }
}
