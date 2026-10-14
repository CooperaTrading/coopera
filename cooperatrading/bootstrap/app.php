<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Auto-create required storage directories if missing on shared hosting
$requiredDirs = [
    dirname(__DIR__).'/storage/framework/views',
    dirname(__DIR__).'/storage/framework/cache/data',
    dirname(__DIR__).'/storage/framework/sessions',
    dirname(__DIR__).'/storage/logs',
    dirname(__DIR__).'/storage/app/public',
    __DIR__.'/cache',
];
foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
}

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'permission' => \App\Http\Middleware\EnsureUserHasPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Emergency fallback: prevent the "facade root has not been set" cascade.
        // This fires BEFORE Laravel's default handler tries View::replaceNamespace(),
        // which crashes if facades aren't fully wired. By returning a Response here,
        // we guarantee the real error message is always visible.
        $exceptions->render(function (\Throwable $e, $request) {
            // Only intercept when the View facade is unavailable
            try {
                $viewRoot = \Illuminate\Support\Facades\View::getFacadeRoot();
                if ($viewRoot !== null) {
                    return null; // Let normal Laravel error rendering proceed
                }
            } catch (\Throwable $facadeCheck) {
                // View facade is broken — fall through to emergency rendering
            }

            $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
            $message = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            $trace = htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8');
            $class = get_class($e);
            $file = htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8');
            $line = $e->getLine();

            $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Error {$status}</title>
<style>
body{font-family:system-ui,sans-serif;background:#0f172a;color:#e2e8f0;padding:40px;margin:0}
.box{max-width:800px;margin:0 auto;background:#1e293b;border:1px solid #334155;border-radius:12px;padding:32px}
h1{color:#f87171;margin-top:0}h2{color:#94a3b8;font-size:14px}
pre{background:#0f172a;border-radius:8px;padding:16px;overflow-x:auto;font-size:13px;line-height:1.5}
.meta{color:#64748b;font-size:13px;margin-top:4px}
</style></head>
<body><div class="box">
<h1>⚠ Application Error ({$status})</h1>
<h2>{$class}</h2>
<p style="color:#fbbf24;font-size:16px">{$message}</p>
<p class="meta">{$file}:{$line}</p>
<pre>{$trace}</pre>
</div></body></html>
HTML;

            return new \Illuminate\Http\Response($html, $status, ['Content-Type' => 'text/html']);
        });
    })->create();

\Illuminate\Support\Facades\Facade::setFacadeApplication($app);

return $app;
