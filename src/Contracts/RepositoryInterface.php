<?php

namespace App\Contracts;

/**
 * Contract for data repository operations
 *
 * Defines standard CRUD operations that all data repositories must implement.
 */
interface RepositoryInterface
{
    /**
     * Retrieve all records
     *
     * @return array An array of all entities
     */
    public function all(): array;

    /**
     * Find a specific record by its identifier
     *
     * @param int $id The record identifier
     * @return array|null The entity data or null if not found
     */
    public function find(int $id): ?array;

    /**
     * Create a new record
     *
     * @param array $data Entity data to persist
     * @return int The ID of the newly created record
     */
    public function create(array $data): int;

    /**
     * Update an existing record
     *
     * @param int $id The record identifier
     * @param array $data Data to update
     * @return bool True on success, false on failure
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a record
     *
     * @param int $id The record identifier
     * @return bool True if record was deleted, false otherwise
     */
    public function delete(int $id): bool;
}
