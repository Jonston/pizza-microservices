<?php

use App\Http\Controllers\OrderController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'Orders test route is working']);
})->name('test');

Route::get('/foo', [OrderController::class, 'index'])->name('orders.index');

/*Route::prefix('foo')->group(function () {
    Route::get('', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
});*/

Route::get('products', function () {
    return response()->json(Product::all());
});