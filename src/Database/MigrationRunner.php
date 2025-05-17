<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use App\Contracts\MigrationInterface;

class MigrationRunner
{
    public function __construct(private array $migrations) {}

    public function up(PDO $pdo): void
    {
        $pdo->beginTransaction();

        try {
            foreach ($this->migrations as $migration) {
                $migration->up($pdo);
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw new \RuntimeException("Migration failed: " . $e->getMessage());
        }
    }

    /**
     * Rolls back migrations in reverse order.
     */
    public function down(PDO $pdo): void
    {
        $pdo->beginTransaction();

        try {
            foreach (array_reverse($this->migrations) as $migration) {
                $migration->down($pdo);
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw new \RuntimeException("Rollback failed: " . $e->getMessage());
        }
    }
}
