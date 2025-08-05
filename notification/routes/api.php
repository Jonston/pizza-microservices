<?php

use App\Events\OrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('test', function () {
    $event = new OrderCreated([
        'id' => 1,
        'status' => 'created',
        'total' => 100.00,
    ]);

    $event = broadcast($event);

    return response()->json([
        'message' => 'Event broadcasted successfully',
        'event' => $event,
    ]);
})->name('test');
