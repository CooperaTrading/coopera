<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$baseDir = dirname(__DIR__);

// 1. Remove dangerous static config/route caches and local Vite dev 'hot' file
$staleCacheFiles = [
    $baseDir . '/bootstrap/cache/config.php',
    $baseDir . '/bootstrap/cache/routes-v7.php',
    $baseDir . '/bootstrap/cache/routes.php',
    $baseDir . '/public/hot',
    __DIR__ . '/hot',
];
foreach ($staleCacheFiles as $staleFile) {
    if (file_exists($staleFile)) {
        @unlink($staleFile);
    }
}

// 2. Ensure all storage and database directories exist with 0775 permissions
$dirsToEnsure = [
    $baseDir . '/storage',
    $baseDir . '/storage/app',
    $baseDir . '/storage/app/public',
    $baseDir . '/storage/app/private',
    $baseDir . '/storage/framework',
    $baseDir . '/storage/framework/views',
    $baseDir . '/storage/framework/cache',
    $baseDir . '/storage/framework/cache/data',
    $baseDir . '/storage/framework/sessions',
    $baseDir . '/storage/logs',
    $baseDir . '/bootstrap/cache',
    $baseDir . '/database',
];

foreach ($dirsToEnsure as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    @chmod($dir, 0775);
}

// 3. Ensure SQLite database file exists with write permissions
$sqliteDb = $baseDir . '/database/database.sqlite';
if (!file_exists($sqliteDb)) {
    @touch($sqliteDb);
}
@chmod($sqliteDb, 0666);

// 4. Ensure public/storage symlink exists for uploaded assets
$storageLink = __DIR__ . '/storage';
if (!file_exists($storageLink) && is_dir($baseDir . '/storage/app/public')) {
    @symlink('../storage/app/public', $storageLink);
}

// Suppress noisy deprecation notices in PHP 8.3+
@ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $baseDir . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $baseDir . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
// Wrapped in try-catch as an absolute last-resort safety net.
try {
    $app = require $baseDir . '/bootstrap/app.php';
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    // This should only fire if BOTH the main handler AND the emergency
    // renderer in bootstrap/app.php failed. Show the real error.
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    $msg = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    $cls = get_class($e);
    $file = htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8');
    $line = $e->getLine();
    $trace = htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8');
    echo <<<HTML
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Error</title>
<style>body{font-family:monospace;background:#111;color:#eee;padding:40px}
pre{background:#222;padding:16px;border-radius:8px;overflow:auto}
h1{color:#f44}</style></head>
<body><h1>Fatal Boot Error</h1>
<p><strong>{$cls}:</strong> {$msg}</p>
<p style="color:#888">{$file}:{$line}</p>
<pre>{$trace}</pre></body></html>
HTML;
    exit(1);
}
