<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Contracts\RepositoryInterface;

/**
 * Repository for product data operations
 *
 * Handles all database interactions for products including
 * CRUD operations.
 */
class ProductRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Retrieve all products
     *
     * @return array Array of all products ordered by name
     */
    public function all(): array
    {
        return $this->execute('SELECT * FROM products')->fetchAll();
    }

    /**
     * Find a specific product by ID
     *
     * @param int $id Product ID
     * @return array|null Product data or null if not found
     */
    public function find(int $id): ?array
    {
        $stmt = $this->execute(
            'SELECT * FROM products WHERE id = :id',
            ['id' => $id]
        );
        return $stmt->fetch() ?: null;
    }

    /**
     * Create a new product
     *
     * @param array $data Product data
     * @return int ID of the newly created product
     */
    public function create(array $data): int
    {
        return $this->insert(
            'INSERT INTO products (name, price) VALUES (:name, :price)',
            [
                'name' => $data['name'],
                'price' => $data['price']
            ]
        );
    }

    /**
     * Update an existing product
     *
     * @param int $id Product ID to update
     * @param array $data Updated product data
     * @return bool True if update was successful, false otherwise
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->execute(
            'UPDATE products SET 
                name = :name, 
                price = :price 
            WHERE id = :id',
            [
                'name' => $data['name'],
                'price' => $data['price'],
                'id' => $id
            ]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a product
     *
     * @param int $id Product ID to delete
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(int $id): bool
    {
        $stmt = $this->execute(
            'DELETE FROM products WHERE id = :id',
            ['id' => $id]
        );
        return $stmt->rowCount() > 0;
    }
}
