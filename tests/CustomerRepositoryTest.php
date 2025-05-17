<?php

declare(strict_types=1);

namespace Tests;

use PDO;
use App\Database\PdoFactory;
use PHPUnit\Framework\TestCase;
use App\Database\DatabaseConnection;
use App\Database\Repositories\CustomerRepository;

/**
 * Unit tests for the CustomerRepository.
 */
final class CustomerRepositoryTest extends TestCase
{
    /**
     * @var CustomerRepository
     */
    private CustomerRepository $repo;

    /**
     * Set up the test environment.
     * Initializes the database and clears the customers table.
     * @return void
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
        $this->repo = new CustomerRepository(DatabaseConnection::getInstance()->getConnection());

        // Clear any existing test data
        DatabaseConnection::getInstance()->getConnection()->exec('DELETE FROM customers');
    }

    /**
     * Test creating a customer record.
     * @return void
     */
    public function testCreateCustomer(): void
    {
        $data = ['name' => 'John Doe', 'address' => '123 Main St'];
        $id = $this->repo->create($data);

        $this->assertGreaterThan(0, $id);
    }
}
