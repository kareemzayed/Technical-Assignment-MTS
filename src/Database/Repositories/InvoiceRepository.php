<?php

declare(strict_types=1);

namespace App\Database\Repositories;

use App\Database\Repositories\BaseRepository;
use App\Contracts\RepositoryInterface;

/**
 * Repository for invoice data operations
 *
 * Handles all database interactions for invoices including
 * CRUD operations.
 */
class InvoiceRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Retrieve all invoices
     *
     * @return array Array of all invoices
     */
    public function all(): array
    {
        return $this->execute('SELECT * FROM invoices')->fetchAll();
    }

    /**
     * Find a specific invoice by ID
     *
     * @param int $id Invoice ID
     * @return array|null Invoice data or null if not found
     */
    public function find(int $id): ?array
    {
        $stmt = $this->execute('SELECT * FROM invoices WHERE id = :id', ['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Create a new invoice
     *
     * @param array $data Invoice data.
     * @return int ID of the newly created invoice
     */
    public function create(array $data): int
    {
        return $this->insert(
            'INSERT INTO invoices (invoice_date, customer_id, grand_total) VALUES (:invoice_date, :customer_id, :grand_total)',
            [
                'invoice_date' => $data['invoice_date'],
                'customer_id' => $data['customer_id'],
                'grand_total' => $data['grand_total'],
            ]
        );
    }

    /**
     * Update an existing invoice
     *
     * @param int $id Invoice ID to update
     * @param array $data Updated invoice data
     * @return bool True if update was successful, false otherwise
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->execute(
            'UPDATE invoices SET 
                invoice_date = :invoice_date, 
                customer_id = :customer_id, 
                grand_total = :grand_total 
            WHERE id = :id',
            [
                'invoice_date' => $data['invoice_date'],
                'customer_id' => $data['customer_id'],
                'grand_total' => $data['grand_total'],
                'id' => $id,
            ]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete an invoice
     *
     * @param int $id Invoice ID to delete
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(int $id): bool
    {
        $stmt = $this->execute(
            'DELETE FROM invoices WHERE id = :id',
            ['id' => $id]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Find invoices for a specific customer
     *
     * @param int $customerId Customer ID
     * @return array Array of customer invoices
     */
    public function findByCustomer(int $customerId): array
    {
        return $this->execute(
            'SELECT * FROM invoices 
            WHERE customer_id = :customer_id 
            ORDER BY invoice_date DESC',
            ['customer_id' => $customerId]
        )->fetchAll();
    }
}
