<?php

use Illuminate\Support\Facades\Route;

// Ruta principal para Vue.js - maneja todas las rutas del frontend
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!api).*$')->name('app');
