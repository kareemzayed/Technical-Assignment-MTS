<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use PDO;
use App\Contracts\MigrationInterface;

/**
 * Migration for creating the invoice_items table
 * 
 * Establishes the relationship between invoices and products through line items.
 */
class CreateInvoiceItemsTableMigration implements MigrationInterface
{
    /**
     * Creates the invoice_items table with foreign key constraints
     *
     * @param PDO $pdo Active database connection
     * @return void
     */
    public function up(PDO $pdo): void
    {
        $pdo->exec('
            CREATE TABLE IF NOT EXISTS invoice_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                invoice_id INTEGER NOT NULL,
                product_id INTEGER NOT NULL,
                quantity INTEGER NOT NULL,
                total DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id)
            )
        ');
    }

    /**
     * Drops the invoice_items table
     *
     * Safely removes the table if it exists.
     * @param PDO $pdo Active database connection
     * @return void
     */
    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS invoice_items');
    }
}
