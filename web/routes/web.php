<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;

Route::get('/', [KasirController::class, 'index'])->name('kasir');

Route::post('/items/add', [KasirController::class, 'addItem'])->name('items.add');
Route::post('/items/remove', [KasirController::class, 'removeItem'])->name('items.remove');
Route::post('/items/clear', [KasirController::class, 'clearItems'])->name('items.clear');

Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');
Route::post('/compare', [KasirController::class, 'compare'])->name('compare');
