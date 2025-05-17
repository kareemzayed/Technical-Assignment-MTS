<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use PDO;
use App\Contracts\MigrationInterface;

class CreateCustomersTableMigration implements MigrationInterface
{
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

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS customers');
    }
}
