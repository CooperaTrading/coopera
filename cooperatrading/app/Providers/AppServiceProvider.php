<?php

namespace App\Providers;

use App\View\Composers\HeaderCategoriesComposer;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Automatic storage symlink setup for shared hosting
        if (!is_dir(public_path('storage')) && file_exists(storage_path('app/public'))) {
            if (is_link(public_path('storage')) || file_exists(public_path('storage'))) {
                @unlink(public_path('storage'));
            }
            @symlink(storage_path('app/public'), public_path('storage'));
        }

        View::composer('layouts.app', HeaderCategoriesComposer::class);
    }
}
