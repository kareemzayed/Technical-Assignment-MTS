<?php

declare(strict_types=1);

namespace App\Http;

use InvalidArgumentException;
use App\Database\DatabaseConnection;
use App\Contracts\RepositoryInterface;
use App\Database\Repositories\InvoiceRepository;
use App\Database\Repositories\ProductRepository;
use App\Database\Repositories\CustomerRepository;
use App\Database\Repositories\InvoiceItemRepository;

/**
 * Maps resource names to their corresponding repository classes and instantiates them.
 */
class ResourceMap
{
    /**
     * Map of resource keys to repository class names.
     *
     * @var array<string, RepositoryInterface>
     */
    protected static array $map = [
        'customers'     => CustomerRepository::class,
        'products'      => ProductRepository::class,
        'invoices'      => InvoiceRepository::class,
        'invoice_items' => InvoiceItemRepository::class,
    ];

    /**
     * Resolve the repository class based on the given resource name.
     *
     * @param string $resource The resource identifier.
     * @return RepositoryInterface The instantiated repository.
     *
     * @throws InvalidArgumentException If the resource is not mapped.
     */
    public static function resolve(string $resource): RepositoryInterface
    {
        $resource = strtolower($resource);

        if (!array_key_exists($resource, self::$map)) {
            http_response_code(404);
            throw new InvalidArgumentException("Unknown resource: $resource");
        }

        $repoClass = self::$map[$resource];
        $pdo = DatabaseConnection::getInstance()->getConnection();

        return new $repoClass($pdo);
    }
}
