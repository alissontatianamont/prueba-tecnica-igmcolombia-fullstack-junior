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

// Ruta para verificar manifest
Route::get('/check-assets', function () {
    $manifestPath = public_path('build/manifest.json');
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        return response()->json([
            'status' => 'success',
            'manifest_exists' => true,
            'manifest' => $manifest,
            'app_url' => config('app.url'),
            'asset_urls' => [
                'css' => asset('build/' . ($manifest['resources/css/app.css']['file'] ?? 'not-found')),
                'js' => asset('build/' . ($manifest['resources/js/app.js']['file'] ?? 'not-found')),
            ]
        ]);
    }
    return response()->json(['status' => 'error', 'message' => 'Manifest not found']);
});

// Ruta de debug con blade
Route::get('/debug', function () {
    return view('debug');
});

// Ruta principal para Vue.js - maneja todas las rutas del frontend
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!api|test).*$')->name('app');
