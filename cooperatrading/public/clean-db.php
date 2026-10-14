<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

echo "<h2>Starting Coopera Shared Hosting Database Initialization...</h2>";

try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$db}", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Disable foreign key checks for dropping
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    
    // Multi-pass drop loop to handle foreign key dependencies cleanly
    for ($pass = 1; $pass <= 5; $pass++) {
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (empty($tables)) break;
        foreach ($tables as $t) {
            try {
                $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
            } catch (Exception $ex) {}
        }
    }

    // Final check to make sure database is 100% empty
    $stmt = $pdo->query("SHOW TABLES");
    $remaining = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($remaining as $rem) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS `{$rem}`");
        } catch (Exception $ex) {}
    }
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    
    echo "<p style='color:green;'><strong>Step 1:</strong> Database successfully cleared of all prior tables!</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'><strong>Step 1 Warning:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

try {
    define('LARAVEL_START', microtime(true));
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    // Disable foreign keys on Laravel connection
    try {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
    } catch (\Throwable $t) {}

    $output1 = new Symfony\Component\Console\Output\BufferedOutput();
    $kernel->call('migrate', ['--force' => true], $output1);
    echo "<h3>Step 2: Migration Output</h3><pre style='background:#f4f4f4;padding:10px;'>" . htmlspecialchars($output1->fetch()) . "</pre>";

    $output2 = new Symfony\Component\Console\Output\BufferedOutput();
    $kernel->call('db:seed', ['--force' => true], $output2);
    echo "<h3>Step 3: Database Seeding Output</h3><pre style='background:#f4f4f4;padding:10px;'>" . htmlspecialchars($output2->fetch()) . "</pre>";

    try {
        $output3 = new Symfony\Component\Console\Output\BufferedOutput();
        $kernel->call('storage:link', [], $output3);
        echo "<h3>Step 4: Storage Link Output</h3><pre style='background:#f4f4f4;padding:10px;'>" . htmlspecialchars($output3->fetch()) . "</pre>";
    } catch (Exception $stEx) {
        echo "<p>Storage link: " . htmlspecialchars($stEx->getMessage()) . "</p>";
    }

    try {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    } catch (\Throwable $t) {}

    echo "<h2 style='color:green;'>SUCCESS! Your website coopera.razielcc.com is fully configured and ready!</h2>";
} catch (Exception $e) {
    echo "<h3 style='color:red;'>Initialization Error:</h3><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
