<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use App\Contracts\MigrationInterface;

/**
 * Executes database migrations in transactional batches
 *
 * Ensures migrations are applied or rolled back atomically.
 */
class MigrationRunner
{
    /**
     * @param array<MigrationInterface> $migrations Migration instances to execute
     */
    public function __construct(private array $migrations)
    {
        array_walk($this->migrations, function ($migration) {
            if (!$migration instanceof MigrationInterface) {
                throw new \InvalidArgumentException(
                    'All migrations must implement MigrationInterface'
                );
            }
        });
    }

    /**
     * Applies all migrations in sequential order
     *
     * @param PDO $pdo Active database connection
     * @return void
     * @throws RuntimeException When any migration fails
     */
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
     * Reverts all migrations in reverse order
     *
     * @param PDO $pdo Active database connection
     * @return void
     * @throws RuntimeException When any rollback fails
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
