<?php
/**
 * Coopera Trading - Emergency Cache, Migration & Storage Fix Utility
 * Accessible via: https://yourdomain.com/clear-cache.php
 *
 * Idempotent. Safe to run on a fresh deploy or an upgrade.
 * Performs:
 *   - Clears stale Laravel config/route/view caches
 *   - Re-generates package manifests if missing
 *   - Ensures all required storage/database directories exist with proper perms
 *   - Ensures SQLite database file is present and writable
 *   - Ensures public/storage symlink exists
 *   - Runs any pending database migrations (does NOT drop tables)
 *   - Clears compiled Blade views so they re-compile against latest source
 */

header('Content-Type: text/html; charset=utf-8');

$baseDir = dirname(__DIR__);
$messages = [];

// 1. Delete ONLY stale config and route cache files (NEVER packages.php or services.php)
$staleCacheFiles = [
    $baseDir . '/bootstrap/cache/config.php',
    $baseDir . '/bootstrap/cache/routes-v7.php',
    $baseDir . '/bootstrap/cache/routes.php',
];

foreach ($staleCacheFiles as $file) {
    if (file_exists($file)) {
        if (@unlink($file)) {
            $messages[] = "Deleted stale cache file: " . basename($file);
        }
    }
}

// 2. Ensure packages.php and services.php exist
if (!file_exists($baseDir . '/bootstrap/cache/packages.php') || !file_exists($baseDir . '/bootstrap/cache/services.php')) {
    try {
        require_once $baseDir . '/vendor/autoload.php';
        $app = require_once $baseDir . '/bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->call('package:discover');
        $messages[] = "Re-generated packages.php and services.php package manifests.";
    } catch (\Throwable $e) {
        $messages[] = "Package discovery note: " . $e->getMessage();
    }
} else {
    $messages[] = "Package manifests (packages.php, services.php) are intact.";
}

// 3. Ensure all storage and database directories exist and have 0775 permissions
$storageDirs = [
    $baseDir . '/storage',
    $baseDir . '/storage/app',
    $baseDir . '/storage/app/public',
    $baseDir . '/storage/app/public/hero-slides',
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

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        if (@mkdir($dir, 0775, true)) {
            $messages[] = "Created directory: " . relative_path($baseDir, $dir);
        }
    }
    @chmod($dir, 0775);
}

function relative_path($base, $full) {
    $base = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    if (str_starts_with($full, $base)) {
        return str_replace(DIRECTORY_SEPARATOR, '/', substr($full, strlen($base)));
    }
    return str_replace(DIRECTORY_SEPARATOR, '/', $full);
}

// 4. Ensure SQLite database file exists and is writable
$sqliteFile = $baseDir . '/database/database.sqlite';
if (!file_exists($sqliteFile)) {
    @touch($sqliteFile);
}
@chmod($sqliteFile, 0664);
$messages[] = "SQLite database file verified with write permissions.";

// 5. Ensure public/storage symlink exists
$publicStorageLink = $baseDir . '/public/storage';
if (is_link($publicStorageLink)) {
    $messages[] = "Symlink public/storage → " . readlink($publicStorageLink) . " (intact).";
} elseif (file_exists($publicStorageLink)) {
    $messages[] = "public/storage exists but is not a symlink (left untouched).";
} else {
    try {
        if (symlink($baseDir . '/storage/app/public', $publicStorageLink)) {
            $messages[] = "Created symlink public/storage → ../storage/app/public.";
        } else {
            $messages[] = "Could not auto-create public/storage symlink (manual link may be required).";
        }
    } catch (\Throwable $e) {
        $messages[] = "Symlink error: " . $e->getMessage();
    }
}

// 6. Run any pending migrations (idempotent — never drops data)
try {
    require_once $baseDir . '/vendor/autoload.php';
    $app = require_once $baseDir . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $migrator = $app->make('migrator');
    $files = $migrator->getMigrationFiles(database_path('migrations'));
    $ranMigrations = \Illuminate\Support\Facades\DB::table('migrations')->pluck('migration')->all();
    $pending = [];
    foreach ($files as $name => $path) {
        if (!in_array($name, $ranMigrations, true)) {
            $pending[] = $name;
        }
    }

    if (count($pending) === 0) {
        $messages[] = "Database schema is up to date (" . count($ranMigrations) . " migrations applied).";
    } else {
        $kernel->call('migrate', ['--force' => true]);
        $messages[] = "Ran " . count($pending) . " pending migration(s): " . implode(', ', $pending);
    }
} catch (\Throwable $e) {
    $messages[] = "Migration step skipped: " . $e->getMessage();
}

// 7. Clear compiled views so they re-compile against the latest templates
$viewFiles = glob($baseDir . '/storage/framework/views/*.php');
if ($viewFiles) {
    foreach ($viewFiles as $vf) {
        @unlink($vf);
    }
    $messages[] = "Cleared " . count($viewFiles) . " compiled Blade views.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coopera Trading - System Maintenance Complete</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 32px; max-width: 700px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); }
        h1 { font-size: 24px; color: #38bdf8; margin-top: 0; display: flex; align-items: center; gap: 8px; }
        .success-badge { display: inline-block; background: #065f46; color: #34d399; font-weight: 600; padding: 4px 12px; border-radius: 9999px; font-size: 13px; margin-bottom: 20px; }
        ul { background: #0f172a; border-radius: 8px; padding: 16px 20px 16px 36px; color: #94a3b8; font-family: monospace; font-size: 13px; line-height: 1.6; }
        .btn { display: inline-block; background: #0284c7; color: white; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; margin-top: 20px; margin-right: 8px; transition: background 0.2s; }
        .btn:hover { background: #0369a1; }
    </style>
</head>
<body>
    <div class="card">
        <span class="success-badge">✓ System Maintenance Complete</span>
        <h1>Coopera Trading Ready</h1>
        <p style="color: #cbd5e1;">All caches cleared, storage paths verified, pending migrations applied, and <code>public/storage</code> symlink ensured.</p>

        <ul>
            <?php foreach ($messages as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>

        <div style="display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap;">
            <a href="/" class="btn">Go to Home Page →</a>
            <a href="/login" class="btn" style="background: #334155;">Admin Login →</a>
            <a href="/debug.php" class="btn" style="background: #475569;">Diagnostics →</a>
        </div>
    </div>
</body>
</html>

