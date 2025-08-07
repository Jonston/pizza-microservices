<?php

use App\Http\Controllers\OrderController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('', [OrderController::class, 'index'])->name('orders.index');
Route::post('', [OrderController::class, 'store'])->name('orders.store');