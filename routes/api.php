<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    
    // Users endpoints
    Route::get('users', [UserController::class, 'index'])
        ->middleware('permission.sanctum:view users')
        ->name('users.index');
    
    Route::post('users', [UserController::class, 'store'])
        ->middleware('permission.sanctum:create users')
        ->name('users.store');
    
    Route::get('users/{id}', [UserController::class, 'show'])
        ->middleware('permission.sanctum:view users')
        ->name('users.show');
    
    Route::patch('users/{id}', [UserController::class, 'update'])
        ->middleware('permission.sanctum:edit users')
        ->name('users.update');
    
    Route::delete('users/{id}', [UserController::class, 'destroy'])
        ->middleware('permission.sanctum:delete users')
        ->name('users.destroy');

    // Clients endpoints
    Route::get('clients', [ClientController::class, 'index'])
        ->middleware('permission.sanctum:view clients')
        ->name('clients.index');
    
    Route::post('clients', [ClientController::class, 'store'])
        ->middleware('permission.sanctum:create clients')
        ->name('clients.store');
    
    Route::get('clients/{id}', [ClientController::class, 'show'])
        ->middleware('permission.sanctum:view clients')
        ->name('clients.show');
    
    Route::patch('clients/{id}', [ClientController::class, 'update'])
        ->middleware('permission.sanctum:edit clients')
        ->name('clients.update');
    
    Route::delete('clients/{id}', [ClientController::class, 'destroy'])
        ->middleware('permission.sanctum:delete clients')
        ->name('clients.destroy');

    Route::patch('clients/{id}/activate', [ClientController::class, 'activate'])
        ->middleware('permission.sanctum:edit clients')
        ->name('clients.activate');
    
    Route::patch('clients/{id}/deactivate', [ClientController::class, 'deactivate'])
        ->middleware('permission.sanctum:edit clients')
        ->name('clients.deactivate');

        // Products endpoints
    Route::get('products', [ProductController::class, 'index'])
        ->middleware('permission.sanctum:view products')
        ->name('products.index');
    
    Route::post('products', [ProductController::class, 'store'])
        ->middleware('permission.sanctum:create products')
        ->name('products.store');
    
    Route::get('products/{id}', [ProductController::class, 'show'])
        ->middleware('permission.sanctum:view products')
        ->name('products.show');
    
    Route::patch('products/{id}', [ProductController::class, 'update'])
        ->middleware('permission.sanctum:edit products')
        ->name('products.update');
    
    Route::delete('products/{id}', [ProductController::class, 'destroy'])
        ->middleware('permission.sanctum:delete products')
        ->name('products.destroy');

        // Invoices endpoints
    Route::get('invoices', [InvoiceController::class, 'index'])
        ->middleware('permission.sanctum:view invoices')
        ->name('invoices.index');
    
    Route::post('invoices', [InvoiceController::class, 'store'])
        ->middleware('permission.sanctum:create invoices')
        ->name('invoices.store');
    
    Route::get('invoices/{id}', [InvoiceController::class, 'show'])
        ->middleware('permission.sanctum:view invoices')
        ->name('invoices.show');
    
    Route::patch('invoices/{id}', [InvoiceController::class, 'update'])
        ->middleware('permission.sanctum:edit invoices')
        ->name('invoices.update');
    
    Route::delete('invoices/{id}', [InvoiceController::class, 'destroy'])
        ->middleware('permission.sanctum:delete invoices')
        ->name('invoices.destroy');

    // Files endpoints
    Route::get('invoices/{id}/file', [FileController::class, 'getInvoiceFileUrl'])
        ->middleware('permission.sanctum:view invoices')
        ->name('invoices.file');
});

Route::get('files/serve/{encryptedPath}', [FileController::class, 'serveInvoiceFile'])
    ->name('files.serve.invoice');
