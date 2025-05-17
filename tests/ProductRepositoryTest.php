<?php

declare(strict_types=1);

namespace Tests;

use PDO;
use App\Database\PdoFactory;
use PHPUnit\Framework\TestCase;
use App\Database\DatabaseConnection;
use App\Repositories\ProductRepository;

/**
 * Unit tests for the ProductRepository class.
 */
final class ProductRepositoryTest extends TestCase
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $repo;

    /**
     * Prepare the test environment.
     */
    protected function setUp(): void
    {
        // Define path to a dedicated test SQLite database
        $dbPath = dirname(__DIR__) . '/database/test.sqlite';

        // Initialize the database connection
        DatabaseConnection::init(
            dbPath: $dbPath,
            pdoFactory: new PdoFactory(),
            options: [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        // Inject the connection into the repository
        $this->repo = new ProductRepository(DatabaseConnection::getInstance()->getConnection());

        // Clear any existing test data
        DatabaseConnection::getInstance()->getConnection()->exec('DELETE FROM products');
    }

    /**
     * Test creating a product in the database.
     * @return void
     */
    public function testCreateProduct(): void
    {
        $data = ['name' => 'butter', 'price' => '31.5'];
        $id = $this->repo->create($data);

        $this->assertGreaterThan(0, $id);
    }
}
