<?php

declare(strict_types=1);

use Src\Database\PdoFactory;
use PHPUnit\Framework\TestCase;
use Src\Database\DatabaseConnection;
use Src\Database\InvoiceSchemaBuilder;
use Src\Repositories\ProductRepository;

final class ProductRepositoryTest extends TestCase
{
    private ProductRepository $repo;

    protected function setUp(): void
    {
        // Use a test database
        $dbPath = dirname(__DIR__) . '/database/test.sqlite';

        // Replace DB path for testing
        DatabaseConnection::init(
            dbPath: $dbPath,
            pdoFactory: new PdoFactory(),
            options: [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
            schemaBuilder: new InvoiceSchemaBuilder()
        );

        $this->repo = new ProductRepository(DatabaseConnection::getInstance()->getConnection());

        // Clean database before each test
        DatabaseConnection::getInstance()->getConnection()->exec('DELETE FROM products');
    }

    public function testCreateProduct(): void
    {
        $data = ['name' => 'butter', 'price' => '31.5'];
        $id = $this->repo->create($data);

        $this->assertGreaterThan(0, $id);
    }
}
