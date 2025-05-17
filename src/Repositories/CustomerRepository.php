<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Contracts\RepositoryInterface;

/**
 * Customer repository handling all database operations for customers
 *
 * Implements CRUD operations for customer entities following the
 * Repository pattern. Acts as an intermediary between business
 * logic and data storage layer.
 */
class CustomerRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Retrieve all customers from database
     *
     * @return array Array of customer records
     */
    public function all(): array
    {
        return $this->execute('SELECT * FROM customers')->fetchAll();
    }

    /**
     * Find a specific customer by ID
     *
     * @param int $id Customer ID
     * @return array|null Customer data or null if not found
     */
    public function find(int $id): ?array
    {
        $stmt = $this->execute(
            'SELECT * FROM customers WHERE id = :id',
            ['id' => $id]
        );
        return $stmt->fetch() ?: null;
    }

    /**
     * Create a new customer record
     *
     * @param array $data Customer data
     * @return int ID of newly created customer
     */
    public function create(array $data): int
    {
        return $this->insert(
            'INSERT INTO customers (name, address) VALUES (:name, :address)',
            [
                'name' => $data['name'],
                'address' => $data['address']
            ]
        );
    }

    /**
     * Update an existing customer record
     *
     * @param int $id Customer ID to update
     * @param array $data New customer data
     * @return bool True if update was successful, false otherwise
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->execute(
            'UPDATE customers SET name = :name, address = :address WHERE id = :id',
            [
                'name' => $data['name'],
                'address' => $data['address'],
                'id' => $id
            ]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a customer record
     *
     * @param int $id Customer ID to delete
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(int $id): bool
    {
        $stmt = $this->execute(
            'DELETE FROM customers WHERE id = :id',
            ['id' => $id]
        );
        return $stmt->rowCount() > 0;
    }
}
