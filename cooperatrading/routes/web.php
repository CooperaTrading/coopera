<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProformaController;
use App\Http\Controllers\Admin\QuoteRequestController as AdminQuoteRequestController;
use App\Http\Controllers\Api\ProductSearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteRequestController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// SEO Directives & Sitemap
Route::get('/robots.txt', function () {
    $content = file_exists(public_path('robots.txt'))
        ? file_get_contents(public_path('robots.txt'))
        : "User-agent: *\nDisallow: /admin\nSitemap: " . url('/sitemap.xml');

    return response($content, 200, [
        'Content-Type' => 'text/plain; charset=utf-8',
    ]);
})->name('robots');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Product search API (for header autocomplete)
Route::get('/api/products/search', [ProductSearchController::class, 'search'])->name('api.products.search');

Route::get('/', HomeController::class)->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/products', [ProductCatalogController::class, 'index'])->name('products');
Route::get('/products/{category:slug}', [ProductCatalogController::class, 'category'])->name('products.category');
Route::get('/products/{category:slug}/{product:slug}', [ProductCatalogController::class, 'show'])->name('products.show');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/verify/proforma/{proforma:proforma_number}', [ProformaController::class, 'verify'])
    ->name('proformas.verify');

Route::get('/quote', [QuoteRequestController::class, 'create'])->name('quote.create');
Route::post('/quote', [QuoteRequestController::class, 'store'])->name('quote.store');
Route::get('/quote/thank-you', [QuoteRequestController::class, 'thankYou'])->name('quote.thank-you');

Route::get('/dashboard', function () {
    if (auth()->user()?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::middleware('permission:categories')->group(function () {
        Route::post('categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
        Route::resource('categories', CategoryController::class)->except(['show']);
    });

    // Products
    Route::middleware('permission:products')->group(function () {
        Route::post('products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle-active');
        Route::resource('products', ProductController::class)->except(['show']);
    });

    // Partners
    Route::middleware('permission:partners')->group(function () {
        Route::post('partners/{partner}/toggle-active', [\App\Http\Controllers\Admin\PartnerController::class, 'toggleActive'])->name('partners.toggle-active');
        Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class)->except(['show']);
    });

    // Hero Slides
    Route::middleware('permission:hero-slides')->group(function () {
        Route::post('hero-slides/reorder', [\App\Http\Controllers\Admin\HeroSlideController::class, 'reorder'])->name('hero-slides.reorder');
        Route::post('hero-slides/{heroSlide}/toggle-active', [\App\Http\Controllers\Admin\HeroSlideController::class, 'toggleActive'])->name('hero-slides.toggle-active');
        Route::resource('hero-slides', \App\Http\Controllers\Admin\HeroSlideController::class)->except(['show'])->parameters([
            'hero-slides' => 'heroSlide',
        ]);
    });

    // Sales & Quotes
    Route::middleware('permission:quotes')->group(function () {
        Route::get('quote-requests', [AdminQuoteRequestController::class, 'index'])->name('quote-requests.index');
        Route::get('quote-requests/{quoteRequest}', [AdminQuoteRequestController::class, 'show'])->name('quote-requests.show');
        Route::get('proformas/create/{quoteRequest}', [ProformaController::class, 'create'])->name('proformas.create');
        Route::post('proformas/create/{quoteRequest}', [ProformaController::class, 'store'])->name('proformas.store');
        Route::get('proformas/{proforma}', [ProformaController::class, 'show'])->name('proformas.show');
        Route::get('proformas/{proforma}/download', [ProformaController::class, 'download'])->name('proformas.download');
        Route::get('proformas/{proforma}/stream', [ProformaController::class, 'stream'])->name('proformas.stream');
    });

    // Proforma Templates
    Route::middleware('permission:templates')->group(function () {
        Route::get('proforma-templates', [\App\Http\Controllers\Admin\ProformaTemplateController::class, 'index'])->name('proforma-templates.index');
        Route::post('proforma-templates', [\App\Http\Controllers\Admin\ProformaTemplateController::class, 'update'])->name('proforma-templates.update');
        Route::post('proforma-templates/reset', [\App\Http\Controllers\Admin\ProformaTemplateController::class, 'reset'])->name('proforma-templates.reset');
    });

    // Admin Users & Team Access
    Route::middleware('permission:users')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';

$setupHandler = function () {
    try {
        // Clear any old caches first
        try {
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
        } catch (\Throwable $e) {}

        $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

        $dropped = [];
        $stmt = $pdo->query('SHOW TABLES');
        if ($stmt) {
            while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                if (!empty($row[0])) {
                    $tableName = $row[0];
                    $pdo->exec("DROP TABLE IF EXISTS `{$tableName}`");
                    $dropped[] = $tableName;
                }
            }
        }

        $knownTables = [
            'proforma_items', 'proformas', 'quote_request_items', 'quote_requests',
            'products', 'categories', 'jobs', 'job_batches', 'failed_jobs',
            'cache', 'cache_locks', 'sessions', 'password_reset_tokens', 'users', 'migrations'
        ];
        foreach ($knownTables as $kt) {
            try {
                $pdo->exec("DROP TABLE IF EXISTS `{$kt}`");
            } catch (\Throwable $t) {}
        }

        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();

        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            $storageOutput = \Illuminate\Support\Facades\Artisan::output();
        } catch (\Throwable $stErr) {
            $storageOutput = $stErr->getMessage();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Database tables dropped, migrated, and seeded successfully!',
            'dropped_tables' => $dropped,
            'migrate' => $migrateOutput,
            'seed' => $seedOutput,
            'storage' => $storageOutput,
        ]);
    } catch (\Throwable $e) {
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo()->exec('SET FOREIGN_KEY_CHECKS = 1');
        } catch (\Throwable $fkErr) {}

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
};

Route::get('/deploy-setup', $setupHandler);
Route::get('/run-fresh-migration', $setupHandler);
Route::get('/wipe-and-migrate-now', $setupHandler);
