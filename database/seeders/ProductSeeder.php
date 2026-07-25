<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Āboli',
            'image' => 'images/products/apple.png',
            'unit' => 'kg',
            'stock_quantity' => 200,
            'unit_price' => 1.29,
        ]);

        Product::create([
            'name' => 'Bumbieri',
            'image' => 'images/products/pear.png',
            'unit' => 'kg',
            'stock_quantity' => 150,
            'unit_price' => 1.79,
        ]);

        Product::create([
            'name' => 'Burkāni',
            'image' => 'images/products/carrot.png',
            'unit' => 'kg',
            'stock_quantity' => 130,
            'unit_price' => 0.99,
        ]);

        Product::create([
            'name' => 'Gurķi',
            'image' => 'images/products/cucumber.png',
            'unit' => 'kg',
            'stock_quantity' => 95,
            'unit_price' => 2.19,
        ]);

        Product::create([
            'name' => 'Tomāti',
            'image' => 'images/products/tomato.png',
            'unit' => 'kg',
            'stock_quantity' => 105,
            'unit_price' => 2.49,
        ]);

        Product::create([
            'name' => 'Kartupeļi',
            'image' => 'images/products/potatoe.png',
            'unit' => 'kg',
            'stock_quantity' => 240,
            'unit_price' => 0.89,
        ]);
    }
}
