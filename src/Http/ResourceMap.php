<?php

declare(strict_types=1);

namespace Src\Http;

use InvalidArgumentException;
use Src\Database\DatabaseConnection;
use Src\Contracts\RepositoryInterface;
use Src\Repositories\InvoiceRepository;
use Src\Repositories\ProductRepository;
use Src\Repositories\CustomerRepository;
use Src\Repositories\InvoiceItemRepository;

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
