<?php

namespace App\Services;

use App\Models\Product;
use Bschmitt\Amqp\Facades\Amqp;

class ProductService
{
    /**
     * Создать новый продукт
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        $product = Product::create($data);

        $data = json_encode([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
        ]);

        $params = [
            'exchange' => 'product_fanout',
            'exchange_type' => 'fanout',
        ];

        Amqp::publish('', $data, $params);

        return $product;
    }
}

