<?php

declare(strict_types=1);

namespace Src\Repositories;

use Src\Repositories\BaseRepository;
use Src\Contracts\RepositoryInterface;

/**
 * Repository for invoice item data operations
 *
 * Handles all database interactions for invoice line items,
 * including CRUD operations and business logic related to
 * invoice items.
 */
class InvoiceItemRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Retrieve all invoice items
     *
     * @return array Array of all invoice items
     */
    public function all(): array
    {
        return $this->execute('SELECT * FROM invoice_items')->fetchAll();
    }

    /**
     * Find a specific invoice item by ID
     *
     * @param int $id Invoice item ID
     * @return array|null Invoice item data or null if not found
     */
    public function find(int $id): ?array
    {
        $stmt = $this->execute(
            'SELECT * FROM invoice_items WHERE id = :id',
            ['id' => $id]
        );
        return $stmt->fetch() ?: null;
    }

    /**
     * Create a new invoice item
     *
     * @param array $data Invoice item data [
     *     'invoice_id' => int,
     *     'product_id' => int,
     *     'quantity' => float,
     *     'total' => float
     * ]
     * @return int ID of the newly created invoice item
     */
    public function create(array $data): int
    {
        return $this->insert(
            'INSERT INTO invoice_items (invoice_id, product_id, quantity, total) VALUES (:invoice_id, :product_id, :quantity, :total)',
            [
                'invoice_id' => $data['invoice_id'],
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'total' => $data['total'],
            ]
        );
    }

    /**
     * Update an existing invoice item
     *
     * @param int $id Invoice item ID to update
     * @param array $data Updated invoice item data
     * @return bool True if update was successful, false otherwise
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->execute(
            'UPDATE invoice_items SET 
                invoice_id = :invoice_id,
                product_id = :product_id, 
                quantity = :quantity,
                total = :total 
            WHERE id = :id',
            [
                'invoice_id' => $data['invoice_id'],
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'total' => $data['total'],
                'id' => $id,
            ]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete an invoice item
     *
     * @param int $id Invoice item ID to delete
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(int $id): bool
    {
        $stmt = $this->execute(
            'DELETE FROM invoice_items WHERE id = :id',
            ['id' => $id]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Find all items for a specific invoice
     *
     * @param int $invoiceId Invoice ID
     * @return array Array of invoice items
     */
    public function findByInvoice(int $invoiceId): array
    {
        return $this->execute(
            'SELECT * FROM invoice_items WHERE invoice_id = :invoice_id',
            ['invoice_id' => $invoiceId]
        )->fetchAll();
    }

    /**
     * Calculate subtotal for all items in an invoice
     *
     * @param int $invoiceId Invoice ID
     * @return float Total amount for all items
     */
    public function getInvoiceSubtotal(int $invoiceId): float
    {
        $result = $this->execute(
            'SELECT SUM(total) as subtotal 
            FROM invoice_items 
            WHERE invoice_id = :invoice_id',
            ['invoice_id' => $invoiceId]
        )->fetch();

        return (float)($result['subtotal'] ?? 0);
    }
}
