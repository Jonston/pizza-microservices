<?php

use App\Http\Controllers\OrderController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('products', function () {
    return response()->json([
        'data' => Product::query()->get(),
        'message' => 'Products endpoint is working',
    ]);
})->name('products.index');

Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('', [OrderController::class, 'index'])->name('index');
    Route::post('', [OrderController::class, 'store'])->name('store');
});

Route::get('amqp', function () {
    $amqpService = app(\Jonston\AmqpLaravel\AMQPService::class);

    try {
        $data = [
            'order_id' => 12345,
            'product_id' => 67890,
            'quantity' => 2,
        ];

        $amqpService->publish(
            exchange: 'orders_exchange',
            routingKey: '',
            message: json_encode($data),
            exchangeType: 'fanout'
        );
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => "Failed to connect to AMQP: {$e->getMessage()}",
        ], 500);
    }
})->name('amqp.test');