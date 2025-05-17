# 🧾 Invoice Management System

This project is a simple invoice management system built with native PHP and designed for importing Excel data and exposing resources via JSON endpoints. It uses a well-structured relational database schema to manage customers, products, invoices, and invoice items.

---

## 🚀 Installation & Setup Guide

Follow these steps to install and run the project locally:

### 1. Clone the Repository

```bash
git clone https://github.com/kareemzayed/Technical-Assignment-MTS.git
cd Technical-Assignment-MTS
```

### 2. Install Dependencies

Make sure you have Composer installed, then run:

```bash
composer install
```

### 3. Import Excel Data

This project includes a custom script to import data from `data.xlsx`. To run the import:

```bash
composer import-excel
```

This will execute the script defined in `composer.json`:

```json
"scripts": {
  "import-excel": "php bin/import-excel data.xlsx",
}
```

### 4. Start the PHP Development Server

Navigate to the project directory (if you're not already in it):

```bash
cd Technical-Assignment-MTS
php -S localhost:8000 -t public
```

The project will now be accessible at `http://localhost:8000`.

### 🌐 Accessing Resources

You can retrieve data from specific tables by passing the `resource` parameter in the URL:

| Resource        | URL Example                                    | Description                  |
| --------------- | ---------------------------------------------- | ---------------------------- |
| `customers`     | [http://localhost:8000?resource=customers]     | Fetches all customer records |
| `products`      | [http://localhost:8000?resource=products]      | Fetches all product records  |
| `invoices`      | [http://localhost:8000?resource=invoices]      | Fetches all invoices         |
| `invoice_items` | [http://localhost:8000?resource=invoice_items] | Fetches all invoice items    |

# Entity Relationship Diagram (ERD)

![Invoice Management ERD](ERD.png)

---

## 📦 Database Tables & Relationships

### 1. `customers`

Stores information about customers.

| Field     | Type | Attributes                  |
| --------- | ---- | --------------------------- |
| `id`      | INT  | PRIMARY KEY, AUTO INCREMENT |
| `name`    | TEXT | NOT NULL                    |
| `address` | TEXT | NOT NULL                    |

- **Relationship**: One customer **has many** invoices.

---

### 2. `invoices`

Represents invoices issued to customers.

| Field          | Type          | Attributes                             |
| -------------- | ------------- | -------------------------------------- |
| `id`           | INT           | PRIMARY KEY, AUTO INCREMENT            |
| `invoice_date` | DATE          | NOT NULL                               |
| `customer_id`  | INT           | FOREIGN KEY → `customers.id`, NOT NULL |
| `grand_total`  | DECIMAL(8, 2) | NOT NULL                               |

- **Relationship**:
  - Each invoice **belongs to** one customer.
  - Each invoice **contains many** invoice items.

---

### 3. `products`

Catalog of all products that can be included in invoices.

| Field   | Type          | Attributes                  |
| ------- | ------------- | --------------------------- |
| `id`    | INT           | PRIMARY KEY, AUTO INCREMENT |
| `name`  | TEXT          | NOT NULL                    |
| `price` | DECIMAL(8, 2) | NOT NULL                    |

- **Relationship**: Products are **included in** many invoice items.

---

### 4. `invoice_items`

Line items associated with each invoice.

| Field        | Type           | Attributes                            |
| ------------ | -------------- | ------------------------------------- |
| `id`         | INT            | PRIMARY KEY, AUTO INCREMENT           |
| `invoice_id` | INT            | FOREIGN KEY → `invoices.id`, NOT NULL |
| `product_id` | INT            | FOREIGN KEY → `products.id`, NOT NULL |
| `quantity`   | INT            | NOT NULL                              |
| `total`      | DECIMAL(10, 2) | NOT NULL                              |

- **Relationship**:
  - Each invoice item **belongs to** one invoice.
  - Each invoice item **references** one product.

---

## 🔗 Relationships Summary

- A **Customer** can have multiple **Invoices**.
- An **Invoice** can contain multiple **Invoice Items**.
- An **Invoice Item** refers to a single **Product**.
- Each **Product** can appear in many **Invoice Items**.

---

## 💡 Notes

- The `grand_total` in `invoices` should equal the sum of `total` fields in its associated `invoice_items`.

---

# 🧪 Testing

This project uses **PHPUnit** to test two methods in the application related to creating customers and products.

## ✅ What’s Tested?

Two key repository methods are tested to ensure proper data creation and database integrity:

| Class                | Method     | Purpose                       |
| -------------------- | ---------- | ----------------------------- |
| `CustomerRepository` | `create()` | Creates a new customer record |
| `ProductRepository`  | `create()` | Creates a new product record  |

## 🧾 Test Environment

To prevent interference with `invoice.sqlite` data, a separate SQLite database `test.sqlite` is used for testing.

## 🛠 Test Initialization

The `setUp()` method is used within the PHPUnit test class to:

- Initialize the `test.sqlite` database.
- Ensure tables exist and are ready.
- Truncate the relevant table before each test to maintain a clean state.

## ▶️ Running the Tests

You can execute the test suite using the following command:

```bash
composer test
```

This command is defined in `composer.json`:

```json
"scripts": {
  "test": "phpunit --colors=always"
}
```

Included the `--colors=always` flag to improve the readability and usability of the test output in the terminal.

### ⚙️ PHPUnit Configuration

PHPUnit is configured using the `phpunit.xml` file located in the project root.

### 📁 Test Directory Structure

.
└── tests/
├── CustomerRepositoryTest.php
└── ProductRepositoryTest.php
