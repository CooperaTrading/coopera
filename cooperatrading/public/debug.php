<?php
/**
 * Coopera Trading - Deep Error Capture Diagnostic
 * This captures the REAL underlying error that's being masked
 * by the "facade root has not been set" cascade.
 */

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', '1');
error_reporting(E_ALL);

$baseDir = dirname(__DIR__);
$errors = [];

// Capture ALL PHP errors/warnings/notices as exceptions
set_error_handler(function($severity, $message, $file, $line) use (&$errors) {
    $type = match($severity) {
        E_DEPRECATED, E_USER_DEPRECATED => 'DEPRECATED',
        E_WARNING, E_USER_WARNING => 'WARNING',
        E_NOTICE, E_USER_NOTICE => 'NOTICE',
        default => 'ERROR',
    };
    $errors[] = "$type: $message in $file:$line";
});

// Step 1: Autoload
require_once $baseDir . '/vendor/autoload.php';

// Step 2: Bootstrap App
$app = require_once $baseDir . '/bootstrap/app.php';

// Step 3: Make & Bootstrap Kernel
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// Step 4: Check .env values
$envChecks = [
    'APP_ENV' => env('APP_ENV'),
    'APP_DEBUG' => env('APP_DEBUG'),
    'APP_KEY' => env('APP_KEY') ? 'SET (' . strlen(env('APP_KEY')) . ' chars)' : 'MISSING!',
    'DB_CONNECTION' => env('DB_CONNECTION'),
    'DB_DATABASE' => env('DB_DATABASE', '(not set)'),
    'LOG_CHANNEL' => env('LOG_CHANNEL'),
    'LOG_DEPRECATIONS_CHANNEL' => var_export(env('LOG_DEPRECATIONS_CHANNEL'), true),
    'LOG_STACK' => var_export(env('LOG_STACK'), true),
    'SESSION_DRIVER' => env('SESSION_DRIVER'),
    'CACHE_STORE' => env('CACHE_STORE'),
];

// Step 5: Check facades
$facadeTests = [];
$facades = [
    'View' => function() { return \Illuminate\Support\Facades\View::getFacadeRoot() !== null; },
    'Route' => function() { return \Illuminate\Support\Facades\Route::getFacadeRoot() !== null; },
    'Config' => function() { return \Illuminate\Support\Facades\Config::getFacadeRoot() !== null; },
    'DB' => function() { return \Illuminate\Support\Facades\DB::getFacadeRoot() !== null; },
    'Log' => function() { return \Illuminate\Support\Facades\Log::getFacadeRoot() !== null; },
    'URL' => function() { return \Illuminate\Support\Facades\URL::getFacadeRoot() !== null; },
];
foreach ($facades as $name => $test) {
    try {
        $facadeTests[$name] = $test() ? '✓ Available' : '✗ NULL ROOT';
    } catch (Throwable $e) {
        $facadeTests[$name] = '✗ ERROR: ' . $e->getMessage();
    }
}

// Step 6: Test route resolution
$routeTest = 'Not tested';
try {
    $router = $app->make('router');
    $routes = $router->getRoutes();
    $request = \Illuminate\Http\Request::create('/', 'GET');
    $route = $routes->match($request);
    $routeTest = 'Route matched: ' . ($route->getActionName() ?? 'Closure') . ' | URI: ' . $route->uri();
} catch (Throwable $e) {
    $routeTest = 'ROUTE ERROR: ' . $e->getMessage();
}

// Step 7: Try to render a simple view
$viewTest = 'Not tested';
try {
    $viewTest = 'View paths: ' . implode(', ', config('view.paths', []));
    $viewTest .= ' | Compiled: ' . config('view.compiled', '(null)');
    
    // Try to compile a simple inline view
    $rendered = \Illuminate\Support\Facades\View::make('home')->render();
    $viewTest .= ' | home.blade.php: RENDERED OK (' . strlen($rendered) . ' bytes)';
} catch (Throwable $e) {
    $viewTest .= ' | RENDER ERROR: ' . $e->getMessage();
    $viewTest .= "\n\nFull trace:\n" . $e->getTraceAsString();
}

// Step 8: Full kernel handle (with cascade prevention)
$routeHandleResult = 'Not tested';
try {
    $request = \Illuminate\Http\Request::create('/', 'GET');
    $response = $kernel->handle($request);
    $routeHandleResult = 'HTTP ' . $response->getStatusCode() . ' (' . strlen($response->getContent()) . ' bytes)';
} catch (Throwable $e) {
    $routeHandleResult = 'HANDLE ERROR: ' . $e->getMessage() . "\n\nTrace:\n" . $e->getTraceAsString();
    
    // Walk the exception chain to find the root cause
    $prev = $e->getPrevious();
    while ($prev) {
        $routeHandleResult .= "\n\n--- CAUSED BY ---\n" . get_class($prev) . ': ' . $prev->getMessage() . "\n" . $prev->getTraceAsString();
        $prev = $prev->getPrevious();
    }
}

restore_error_handler();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coopera Trading - Deep Diagnostic</title>
    <style>
        body { font-family: monospace; background: #0b1120; color: #e2e8f0; padding: 30px; margin: 0; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { color: #f59e0b; }
        h2 { color: #38bdf8; border-bottom: 1px solid #334155; padding-bottom: 8px; margin-top: 30px; }
        .box { background: #1e293b; border: 1px solid #334155; border-radius: 8px; padding: 16px; margin: 12px 0; overflow-x: auto; }
        .ok { color: #34d399; }
        .err { color: #f87171; }
        .warn { color: #fbbf24; }
        pre { white-space: pre-wrap; word-break: break-all; margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 12px; border-bottom: 1px solid #1e293b; }
        td:first-child { color: #94a3b8; width: 250px; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔬 Deep Error Capture Diagnostic</h1>

    <h2>1. PHP Errors/Warnings Captured During Bootstrap</h2>
    <div class="box">
        <?php if (empty($errors)): ?>
            <span class="ok">No PHP errors/warnings during bootstrap.</span>
        <?php else: ?>
            <?php foreach ($errors as $err): ?>
                <div class="<?= str_contains($err, 'ERROR') ? 'err' : 'warn' ?>"><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <h2>2. Environment Variables (.env)</h2>
    <div class="box">
        <table>
            <?php foreach ($envChecks as $key => $val): ?>
                <tr>
                    <td><?= $key ?></td>
                    <td class="<?= ($val === null || $val === 'NULL' || str_contains((string)$val, 'MISSING')) ? 'err' : 'ok' ?>">
                        <?= htmlspecialchars((string)$val) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>3. Facade Availability After Bootstrap</h2>
    <div class="box">
        <table>
            <?php foreach ($facadeTests as $name => $result): ?>
                <tr>
                    <td><?= $name ?></td>
                    <td class="<?= str_starts_with($result, '✓') ? 'ok' : 'err' ?>"><?= htmlspecialchars($result) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>4. Route Resolution Test</h2>
    <div class="box <?= str_contains($routeTest, 'ERROR') ? 'err' : 'ok' ?>">
        <pre><?= htmlspecialchars($routeTest) ?></pre>
    </div>

    <h2>5. View Rendering Test</h2>
    <div class="box <?= str_contains($viewTest, 'ERROR') ? 'err' : 'ok' ?>">
        <pre><?= htmlspecialchars($viewTest) ?></pre>
    </div>

    <h2>6. Full Request Handle (GET /)</h2>
    <div class="box <?= str_contains($routeHandleResult, 'ERROR') ? 'err' : 'ok' ?>">
        <pre><?= htmlspecialchars($routeHandleResult) ?></pre>
    </div>
</div>
</body>
</html>
