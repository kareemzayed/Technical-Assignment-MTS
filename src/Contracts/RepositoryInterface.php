<?php

namespace Src\Contracts;

interface RepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?array;
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
