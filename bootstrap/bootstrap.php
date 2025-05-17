<?php

use App\Database\DatabaseConnection;
use App\Database\PdoFactory;

/**
 * Database Initialization Script
 * 
 * Configures and initializes the application database connection
 * with proper schema setup and connection settings.
 */

// Database file path configuration
$databasePath = dirname(__DIR__, 1) . '/database/invoice.sqlite';

// Initialize database connection with schema builder
DatabaseConnection::init(
    dbPath: $databasePath,
    pdoFactory: new PdoFactory(),
    options: [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
