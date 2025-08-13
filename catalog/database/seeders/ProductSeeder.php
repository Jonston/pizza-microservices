<?php

namespace Database\Seeders;

use App\Services\ProductService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function run(): void
    {
        $files = Storage::disk('public')->files('images');

        $this->productService->create([
            'id' => (string) Str::uuid(),
            'name' => 'Four Cheese Pizza',
            'price' => mt_rand(200, 350),
            'image' => Storage::disk('public')->url($files[0]),
        ]);

        $this->productService->create([
            'id' => (string) Str::uuid(),
            'name' => 'Mushroom & Ham Pizza',
            'price' => mt_rand(200, 350),
            'image' => Storage::disk('public')->url($files[1]),
        ]);

        $this->productService->create([
            'id' => (string) Str::uuid(),
            'name' => 'Tuscana Pizza',
            'price' => mt_rand(200, 350),
            'image' => Storage::disk('public')->url($files[2]),
        ]);
    }
}
