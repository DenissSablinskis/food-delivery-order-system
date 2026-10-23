<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderedProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase {
    use RefreshDatabase;

    public function test_customer_can_create_order(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $operator = User::factory()->create([
            'role' => 'operator',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 10,
            'unit_price' => 1.50,
        ]);

        $response = $this->actingAs($customer)->postJson('/checkout', [
            'address' => 'Test address',
            'name' => 'Test',
            'surname' => 'Customer',
            'cart' => [
                [
                    'id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'operator_id' => $operator->id,
            'status' => 'new',
            'delivery_address' => 'Test address',
        ]);

        $order = Order::where('user_id', $customer->id)->firstOrFail();

        $this->assertDatabaseHas('ordered_products', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_order_cannot_be_created_when_stock_is_insufficient(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        User::factory()->create([
            'role' => 'operator',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 2,
            'unit_price' => 1.50,
        ]);

        $response = $this->actingAs($customer)->postJson('/checkout', [
            'address' => 'Test address',
            'name' => 'Test',
            'surname' => 'Customer',
            'cart' => [
                [
                    'id' => $product->id,
                    'quantity' => 3,
                ],
            ],
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('orders', [
            'user_id' => $customer->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 2,
        ]);
    }

    public function test_order_cannot_be_created_when_no_operator_is_available(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 10,
            'unit_price' => 1.50,
        ]);

        $response = $this->actingAs($customer)->postJson('/checkout', [
            'address' => 'Test address',
            'name' => 'Test',
            'surname' => 'Customer',
            'cart' => [
                [
                    'id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('orders', [
            'user_id' => $customer->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 10,
        ]);
    }

    public function test_order_cannot_be_created_with_empty_cart(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->postJson('/checkout', [
            'address' => 'Test address',
            'name' => 'Test',
            'surname' => 'Customer',
            'cart' => [],
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('orders', [
            'user_id' => $customer->id,
        ]);
    }

    public function test_order_cannot_be_created_with_nonexistent_product(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        User::factory()->create([
            'role' => 'operator',
        ]);

        $response = $this->actingAs($customer)->postJson('/checkout', [
            'address' => 'Test address',
            'name' => 'Test',
            'surname' => 'Customer',
            'cart' => [
                [
                    'id' => 99999,
                    'quantity' => 1,
                ],
            ],
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('orders', [
            'user_id' => $customer->id,
        ]);
    }

    public function test_order_cannot_be_created_with_zero_product_quantity(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        User::factory()->create([
            'role' => 'operator',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 10,
            'unit_price' => 1.50,
        ]);

        $response = $this->actingAs($customer)->postJson('/checkout', [
            'address' => 'Test address',
            'name' => 'Test',
            'surname' => 'Customer',
            'cart' => [
                [
                    'id' => $product->id,
                    'quantity' => 0,
                ],
            ],
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('orders', [
            'user_id' => $customer->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 10,
        ]);
    }

    public function test_order_cannot_be_created_with_negative_product_quantity(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        User::factory()->create([
            'role' => 'operator',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 10,
            'unit_price' => 1.50,
        ]);

        $response = $this->actingAs($customer)->postJson('/checkout', [
            'address' => 'Test address',
            'name' => 'Test',
            'surname' => 'Customer',
            'cart' => [
                [
                    'id' => $product->id,
                    'quantity' => -1,
                ],
            ],
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('orders', [
            'user_id' => $customer->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 10,
        ]);
    }

    public function test_guest_cannot_create_order(): void
    {
        $response = $this->postJson('/checkout', [
            'address' => 'Test address',
            'name' => 'Test',
            'surname' => 'Customer',
            'cart' => [
                [
                    'id' => 1,
                    'quantity' => 1,
                ],
            ],
        ]);

        $response->assertUnauthorized();

        $this->assertDatabaseCount('orders', 0);
    }

    
}
