<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // This will create 5 Products with 3 reviews each
        Product::factory(count: 5)
            ->has(
                Review::factory(count: 3)
                ->state(function (array $attributes, Product $product) {
                    return ['product_id' => $product->id];
                })
            )
            ->create();
    }
}
