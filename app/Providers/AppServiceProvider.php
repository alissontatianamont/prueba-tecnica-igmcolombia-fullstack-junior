<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Middleware\PermissionMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('permission', PermissionMiddleware::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(\App\Repositories\ClientRepositoryInterface::class, \App\Repositories\ClientRepository::class);
        $this->app->bind(\App\Repositories\ProductRepositoryInterface::class, \App\Repositories\ProductRepository::class);
        $this->app->bind(\App\Repositories\InvoiceRepositoryInterface::class, \App\Repositories\InvoiceRepository::class);
        $this->app->bind(\App\Repositories\InvoiceFileRepositoryInterface::class, \App\Repositories\InvoiceFileRepository::class);
    }
}
