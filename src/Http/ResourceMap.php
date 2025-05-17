<?php

declare(strict_types=1);

namespace App\Http;

use InvalidArgumentException;
use App\Database\DatabaseConnection;
use App\Contracts\RepositoryInterface;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\InvoiceItemRepository;

class ResourceMap
{
    /**
     * Map of available repositories
     */
    protected static array $map = [
        'customers' => CustomerRepository::class,
        'products' => ProductRepository::class,
        'invoices' => InvoiceRepository::class,
        'invoice_items' => InvoiceItemRepository::class,
    ];

    public static function resolve(string $resource): RepositoryInterface
    {
        $resource = strtolower($resource);

        if (!array_key_exists($resource, self::$map)) {
            http_response_code(404);
            throw new InvalidArgumentException("Unknown resource: $resource");
        }

        $class = self::$map[$resource];
        $pdo = DatabaseConnection::getInstance()->getConnection();

        return new $class($pdo);
    }
}
