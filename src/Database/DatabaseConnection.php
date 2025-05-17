<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use App\Database\PdoFactory;
use App\Database\Migrations\CreateCustomersTableMigration;
use App\Database\Migrations\CreateInvoiceItemsTableMigration;
use App\Database\Migrations\CreateInvoicesTableMigration;
use App\Database\Migrations\CreateProductsTableMigration;

/**
 * Database connection handler (Singleton pattern)
 *
 * Manages database connections with lazy initialization and provides
 * thread-safe access to the PDO instance. Handles schema initialization
 * and ensures proper database directory structure.
 */
class DatabaseConnection
{
    /** @var self|null Singleton instance */
    private static ?self $instance = null;

    /** @var PDO|null Active database connection */
    private ?PDO $connection = null;

    /**
     * Private constructor to enforce singleton pattern
     *
     * @param string $dbPath Path to database file
     * @param PdoFactory $pdoFactory Factory for creating PDO instances
     * @param array $options Additional PDO connection options
     */
    private function __construct(
        private readonly string $dbPath,
        private readonly PdoFactory $pdoFactory,
        private readonly array $options = [],
    ) {}

    /**
     * Prevent cloning of singleton instance
     */
    private function __clone() {}

    /**
     * Prevent unserialization of singleton instance
     *
     * @throws \RuntimeException Always throws when attempted
     */
    public function __wakeup()
    {
        throw new \RuntimeException("Cannot unserialize singleton");
    }

    /**
     * Initialize the singleton instance
     *
     * @param string $dbPath Path to SQLite database file
     * @param PdoFactory $pdoFactory Factory for creating PDO instances
     * @param array $options Additional PDO connection options
     */
    public static function init(
        string $dbPath,
        PdoFactory $pdoFactory,
        array $options = [],
    ): void {
        if (self::$instance === null) {
            self::$instance = new self($dbPath, $pdoFactory, $options);
        }
    }

    /**
     * Get the singleton instance
     *
     * @return self
     *
     * @throws RuntimeException If instance not initialized
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            throw new \RuntimeException("DatabaseConnection must be initialized first using init().");
        }

        return self::$instance;
    }

    /**
     * Get the database connection (lazy initialization)
     *
     * @return PDO Active PDO connection
     *
     * @throws RuntimeException If connection fails or directory creation fails
     */
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $this->ensureDirectoryExists();

            $dsn = 'sqlite:' . $this->dbPath;

            try {
                $this->connection = $this->pdoFactory->create($dsn, $this->options);
                $this->connection->exec('PRAGMA foreign_keys = ON');

                $migrationRunner = new MigrationRunner([
                    'create_customers_table' => new CreateCustomersTableMigration(),
                    'create_invoice_items_table' => new CreateInvoiceItemsTableMigration(),
                    'create_invoices_table' => new CreateInvoicesTableMigration(),
                    'create_products_table' => new CreateProductsTableMigration(),
                ]);
                $migrationRunner->up($this->connection);
            } catch (\Throwable $e) {
                throw new \RuntimeException("Database connection failed: " . $e->getMessage(), (int)$e->getCode(), $e);
            }
        }

        return $this->connection;
    }

    /**
     * Ensure the database directory exists
     *
     * @throws \RuntimeException If directory creation fails
     */
    private function ensureDirectoryExists(): void
    {
        $dir = dirname($this->dbPath);

        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \RuntimeException(
                sprintf('Directory "%s" could not be created', $dir)
            );
        }
    }
}
