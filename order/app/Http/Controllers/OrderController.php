<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): JsonResponse
    {
        $orders = Order::all();

        return response()->json([
            'message' => 'Orders retrieved successfully',
            'orders' => $orders
        ]);
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->create($request->validated());

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
    }
}
