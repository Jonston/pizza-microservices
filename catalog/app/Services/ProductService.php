<?php

namespace App\Services;

use App\Models\Product;
use Jonston\AmqpLaravel\AMQPService;

class ProductService
{
    public function __construct(AMQPService $amqpService)
    {
        $this->amqpService = $amqpService;
    }

    public function create(array $data): Product
    {
        $product = Product::create($data);

        $data = json_encode([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
        ]);

        $this->amqpService->publish('catalog_exchange', 'products.created', $data);

        return $product;
    }

    public function truncate(): void
    {
        Product::truncate();

        $this->amqpService->publish('catalog_exchange', 'products.truncated', 'truncate products');
    }
}

