<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (function_exists('opcache_reset')) {
    @opcache_reset();
}

// Delete any cached configuration/routes files that lock old state
@unlink(__DIR__ . '/../bootstrap/cache/config.php');
@unlink(__DIR__ . '/../bootstrap/cache/routes-v7.php');
@unlink(__DIR__ . '/../bootstrap/cache/routes.php');

// Read DB credentials from .env
$envFile = __DIR__ . '/../.env';
$envVars = [];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        list($name, $value) = explode('=', $line, 2);
        $envVars[trim($name)] = trim(trim($value), '"\'');
    }
}

$host = $envVars['DB_HOST'] ?? 'localhost';
$db   = $envVars['DB_DATABASE'] ?? 'razielmu_coopera';
$user = $envVars['DB_USERNAME'] ?? 'razielmu_user';
$pass = $envVars['DB_PASSWORD'] ?? '6657Razi@';
$port = $envVars['DB_PORT'] ?? '3306';

echo "<h2>Starting Native MySQL Database Setup for Coopera...</h2>";

try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$db}", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    $queries = [
        "CREATE TABLE IF NOT EXISTS `categories` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `description` text COLLATE utf8mb4_unicode_ci,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `categories_slug_unique` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `products` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `category_id` bigint unsigned NOT NULL,
          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `description` text COLLATE utf8mb4_unicode_ci,
          `unit_of_measure` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'piece',
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `products_slug_unique` (`slug`),
          KEY `products_category_id_index` (`category_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `quote_requests` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `message` text COLLATE utf8mb4_unicode_ci,
          `status` enum('pending','processed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `quote_requests_status_index` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `quote_request_items` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `quote_request_id` bigint unsigned NOT NULL,
          `product_id` bigint unsigned DEFAULT NULL,
          `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `unit_of_measure` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'piece',
          `quantity` int unsigned NOT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `proformas` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `quote_request_id` bigint unsigned NOT NULL,
          `proforma_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `issue_date` date NOT NULL,
          `validity_date` date NOT NULL,
          `payment_terms` text COLLATE utf8mb4_unicode_ci,
          `delivery_time` text COLLATE utf8mb4_unicode_ci,
          `bank_details` text COLLATE utf8mb4_unicode_ci,
          `notes` text COLLATE utf8mb4_unicode_ci,
          `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
          `vat` decimal(12,2) NOT NULL DEFAULT '0.00',
          `total` decimal(12,2) NOT NULL DEFAULT '0.00',
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `proformas_proforma_number_unique` (`proforma_number`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `proforma_items` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `proforma_id` bigint unsigned NOT NULL,
          `product_id` bigint unsigned DEFAULT NULL,
          `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `unit_of_measure` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'piece',
          `quantity` int unsigned NOT NULL,
          `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
          `total_price` decimal(12,2) NOT NULL DEFAULT '0.00',
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `users` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `email_verified_at` timestamp NULL DEFAULT NULL,
          `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `is_admin` tinyint(1) NOT NULL DEFAULT '0',
          `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `users_email_unique` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
          `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `sessions` (
          `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `user_id` bigint unsigned DEFAULT NULL,
          `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `user_agent` text COLLATE utf8mb4_unicode_ci,
          `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
          `last_activity` int NOT NULL,
          PRIMARY KEY (`id`),
          KEY `sessions_user_id_index` (`user_id`),
          KEY `sessions_last_activity_index` (`last_activity`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `cache` (
          `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
          `expiration` int NOT NULL,
          PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `cache_locks` (
          `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `expiration` int NOT NULL,
          PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `jobs` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
          `attempts` tinyint unsigned NOT NULL,
          `reserved_at` int unsigned DEFAULT NULL,
          `available_at` int unsigned NOT NULL,
          `created_at` int unsigned NOT NULL,
          PRIMARY KEY (`id`),
          KEY `jobs_queue_index` (`queue`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `job_batches` (
          `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `total_jobs` int NOT NULL,
          `pending_jobs` int NOT NULL,
          `failed_jobs` int NOT NULL,
          `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
          `options` mediumtext COLLATE utf8mb4_unicode_ci,
          `cancelled_at` int DEFAULT NULL,
          `created_at` int NOT NULL,
          `finished_at` int DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `failed_jobs` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
          `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
          `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
          `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
          `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS `migrations` (
          `id` int unsigned NOT NULL AUTO_INCREMENT,
          `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `batch` int NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    ];

    foreach ($queries as $q) {
        $pdo->exec($q);
    }

    // Register migrations as completed
    $migrationsList = [
        '0001_01_01_000000_create_users_table',
        '0001_01_01_000001_create_cache_table',
        '0001_01_01_000002_create_jobs_table',
        '2026_07_24_141210_create_categories_table',
        '2026_07_24_141211_create_products_table',
        '2026_07_24_141212_create_quote_requests_table',
        '2026_07_24_141213_create_quote_request_items_table',
        '2026_07_24_141214_create_proformas_table',
        '2026_07_24_141215_create_proforma_items_table'
    ];

    $checkMig = $pdo->query("SELECT migration FROM migrations")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($migrationsList as $m) {
        if (!in_array($m, $checkMig)) {
            $stmtM = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, 1)");
            $stmtM->execute([$m]);
        }
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    echo "<p style='color:green;'><strong>Step 1 & 2:</strong> All MySQL tables created and migrations registered successfully!</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>DB Creation Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Now boot Laravel and seed initial data
try {
    define('LARAVEL_START', microtime(true));
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    config([
        'database.default' => 'mysql',
        'database.connections.mysql.driver' => 'mysql',
        'database.connections.mysql.host' => $host,
        'database.connections.mysql.port' => $port,
        'database.connections.mysql.database' => $db,
        'database.connections.mysql.username' => $user,
        'database.connections.mysql.password' => $pass,
    ]);
    \Illuminate\Support\Facades\DB::purge();

    $outputSeeder = new Symfony\Component\Console\Output\BufferedOutput();
    $kernel->call('db:seed', ['--force' => true], $outputSeeder);
    echo "<h3>Step 3: Database Seeding Output</h3><pre style='background:#efe;padding:12px;border:1px solid #ccc;'>" . htmlspecialchars($outputSeeder->fetch()) . "</pre>";

    try {
        $outputStorage = new Symfony\Component\Console\Output\BufferedOutput();
        $kernel->call('storage:link', [], $outputStorage);
        echo "<h3>Step 4: Storage Link Output</h3><pre style='background:#eef;padding:12px;border:1px solid #ccc;'>" . htmlspecialchars($outputStorage->fetch()) . "</pre>";
    } catch (Exception $stEx) {}

    echo "<h1 style='color:green;'>🎉 DEPLOYMENT COMPLETE! https://coopera.razielcc.com IS FULLY LIVE & READY! 🎉</h1>";
} catch (Exception $e) {
    echo "<h3 style='color:red;'>Laravel Seeding Error:</h3><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
