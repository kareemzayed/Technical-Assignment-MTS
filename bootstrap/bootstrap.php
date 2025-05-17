<?php

use App\Database\DatabaseConnection;
use App\Database\PdoFactory;

/**
 * Database Initialization Script
 */

// Database file path configuration
$databasePath = dirname(__DIR__, 1) . '/database/invoice.sqlite';

// Initialize database connection
DatabaseConnection::init(
    dbPath: $databasePath,
    pdoFactory: new PdoFactory(),
    options: [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
