<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/test', function () {
    return response()->json(['message' => 'Catalog test route is working']);
})->name('test');

Route::get('/products', function () {
    $products = Product::all();
    return response()->json($products);
})->name('products');

Route::get('/storage', function () {
    $files = Storage::disk('public')->files('images');

    $urls = array_map(function ($file) {
        return '<a href="' . url(Storage::disk('public')->url($file)) . '">' . basename($file) . '</a>';
    }, $files);

    return implode('<br>', $urls);
})->name('storage');

