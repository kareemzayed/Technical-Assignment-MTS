<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use PDO;
use App\Contracts\MigrationInterface;

class CreateInvoicesTableMigration implements MigrationInterface
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('
             CREATE TABLE IF NOT EXISTS invoices (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                invoice_date DATE NOT NULL,
                customer_id INTEGER NOT NULL,
                grand_total DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
            )
        ');
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS invoices');
    }
}
