<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\InvoiceItemRepository;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

/**
 * Excel Data Importer Service
 * 
 * Handles importing of Excel spreadsheet data into database tables
 * using the repository pattern. Processes invoices, customers,
 * products, and invoice items while maintaining relationships.
 */
class ExcelDataImporter
{
    /**
     * @param CustomerRepository $customerRepository Repository for customer operations
     * @param ProductRepository $productRepository Repository for product operations
     * @param InvoiceRepository $invoiceRepository Repository for invoice operations
     * @param InvoiceItemRepository $invoiceItemRepository Repository for invoice item operations
     */
    public function __construct(
        private CustomerRepository $customerRepository,
        private ProductRepository $productRepository,
        private InvoiceRepository $invoiceRepository,
        private InvoiceItemRepository $invoiceItemRepository
    ) {}


    /**
     * Import data from Excel file to database tables
     *
     * @param string $filePath Path to the Excel (.xlsx) file
     * @return array|null Summary of imported records count, null for fail.
     */
    public function import(string $filePath): ?array
    {
        try {
            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open($filePath);

            $importSummary = [
                'customers' => 0,
                'products' => 0,
                'invoices' => 0,
                'invoice_items' => 0
            ];

            $processedInvoices = [];
            $processedCustomers = [];
            $processedProducts = [];

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                    // Skip header row
                    if ($rowIndex === 1) {
                        continue;
                    }

                    $cells = $row->getCells();

                    // Extract data from cells
                    $invoiceData = $this->extractInvoiceData($cells);
                    $customerData = $this->extractCustomerData($cells);
                    $productData = $this->extractProductData($cells);
                    $itemData = $this->extractItemData($cells, $invoiceData['invoice_number']);

                    // Process customer if not already processed
                    if (!isset($processedCustomers[$customerData['name']])) {
                        $customerId = $this->customerRepository->create($customerData);
                        $processedCustomers[$customerData['name']] = $customerId;
                        $importSummary['customers']++;
                    }

                    // Process product if not already processed
                    if (!isset($processedProducts[$productData['name']])) {
                        $productId = $this->productRepository->create($productData);
                        $processedProducts[$productData['name']] = $productId;
                        $importSummary['products']++;
                    }

                    // Process invoice if not already processed
                    if (!isset($processedInvoices[$invoiceData['invoice_number']])) {
                        $invoiceId = $this->invoiceRepository->create([
                            'invoice_date' => $this->convertExcelDate($invoiceData['invoice_date']),
                            'customer_id' => $processedCustomers[$customerData['name']],
                            'grand_total' => $invoiceData['grand_total']
                        ]);
                        $processedInvoices[$invoiceData['invoice_number']] = $invoiceId;
                        $importSummary['invoices']++;
                    }

                    // Process invoice item
                    $this->invoiceItemRepository->create([
                        'invoice_id' => $processedInvoices[$invoiceData['invoice_number']],
                        'product_id' => $processedProducts[$productData['name']],
                        'quantity' => $itemData['quantity'],
                        'total' => $itemData['total']
                    ]);
                    $importSummary['invoice_items']++;
                }
            }

            $reader->close();

            return $importSummary;
        } catch (RuntimeException $e) {
            error_log("Error Reading The Excel File: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract invoice data from spreadsheet cells
     *
     * @param array $cells Row cells from spreadsheet
     * @return array
     */
    private function extractInvoiceData(array $cells): array
    {
        return [
            'invoice_number' => $cells[0]->getValue(),
            'invoice_date' => $cells[1]->getValue(),
            'grand_total' => $cells[8]->getValue()
        ];
    }

    /**
     * Extract customer data from spreadsheet cells
     *
     * @param array $cells Row cells from spreadsheet
     * @return array
     */
    private function extractCustomerData(array $cells): array
    {
        return [
            'name' => $cells[2]->getValue(),
            'address' => $cells[3]->getValue()
        ];
    }

    /**
     * Extract product data from spreadsheet cells
     *
     * @param array $cells Row cells from spreadsheet
     * @return array
     */
    private function extractProductData(array $cells): array
    {
        return [
            'name' => $cells[4]->getValue(),
            'price' => $cells[6]->getValue()
        ];
    }

    /**
     * Extract invoice item data from spreadsheet cells
     *
     * @param array $cells Row cells from spreadsheet
     * @param int $invoiceNumber Related invoice number
     * @return array
     */
    private function extractItemData(array $cells, int $invoiceNumber): array
    {
        return [
            'invoice_number' => $invoiceNumber,
            'quantity' => $cells[5]->getValue(),
            'total' => $cells[7]->getValue()
        ];
    }

    /**
     * Convert Excel date value to Y-m-d format
     *
     * @param float $excelDate Excel date serial number
     * @return string Date in Y-m-d format
     */
    private function convertExcelDate(float $excelDate): string
    {
        $unixTimestamp = (int) ($excelDate - 25569) * 86400;
        return date('Y-m-d', $unixTimestamp);
    }
}
