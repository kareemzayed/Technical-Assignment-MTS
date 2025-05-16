<?php

declare(strict_types=1);

require dirname(__DIR__, 1) . '/bootstrap/bootstrap.php';

use Src\Services\ExcelDataImporter;
use Src\Repositories\CustomerRepository;
use Src\Repositories\ProductRepository;
use Src\Repositories\InvoiceRepository;
use Src\Repositories\InvoiceItemRepository;
use Src\Database\DatabaseConnection;

$pdo = DatabaseConnection::getInstance()->getConnection();
$customerRepo = new CustomerRepository($pdo);
$productRepo = new ProductRepository($pdo);
$invoiceRepo = new InvoiceRepository($pdo);
$invoiceItemRepo = new InvoiceItemRepository($pdo);

$importer = new ExcelDataImporter(
    $customerRepo,
    $productRepo,
    $invoiceRepo,
    $invoiceItemRepo
);

$result = $importer->import(dirname(__DIR__, 1) . '/data.xlsx');

echo "<pre>";
print_r($result);
echo "</pre>";
