<?php

use Illuminate\Support\Facades\Route;

// Ruta de prueba simple
Route::get('/test', function () {
    return response()->json([
        'status' => 'success', 
        'message' => 'Laravel está funcionando',
        'timestamp' => now(),
        'env' => app()->environment()
    ]);
});

// Ruta de prueba de vista simple
Route::get('/test-view', function () {
    return '<h1>Laravel View Test</h1><p>Si ves esto, Laravel está sirviendo vistas correctamente</p>';
});

// Ruta de debug con blade
Route::get('/debug', function () {
    return view('debug');
});

// Ruta principal para Vue.js - maneja todas las rutas del frontend
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!api|test).*$')->name('app');
