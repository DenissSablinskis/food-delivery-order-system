<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_cancel_order_and_restore_product_stock(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        $response = $this->actingAs($admin)
            ->patch("/admin/orders/{$order->id}/status", [
                'status' => 'cancelled',
            ]);

        $response->assertRedirect(route('admin.orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 10,
        ]);
    }

    public function test_admin_cannot_change_delivered_order_status(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'delivered',
        ]);

        $response = $this->actingAs($admin)
            ->patch("/admin/orders/{$order->id}/status", [
                'status' => 'cancelled',
            ]);

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'delivered',
        ]);
    }

    public function test_admin_cannot_cancel_order_twice(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($admin)
            ->patch("/admin/orders/{$order->id}/status", [
                'status' => 'cancelled',
            ]);

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_admin_can_restore_cancelled_order_and_deduct_product_stock(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

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

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'cancelled',
        ]);

        OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        $response = $this->actingAs($admin)
            ->patch("/admin/orders/{$order->id}/status", [
                'status' => 'new',
            ]);

        $response->assertRedirect(route('admin.orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'new',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_admin_cannot_restore_cancelled_order_when_stock_is_insufficient(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 1,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'cancelled',
        ]);

        OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        $response = $this->actingAs($admin)
            ->patch("/admin/orders/{$order->id}/status", [
                'status' => 'new',
            ]);

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 1,
        ]);
    }

    public function test_admin_can_increase_ordered_product_quantity(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $orderedProduct = OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        $response = $this->actingAs($admin)->patch(
            "/admin/orders/{$order->id}/products/{$orderedProduct->id}/increase"
        );

        $response->assertRedirect(route('admin.orders.index'));

        $this->assertDatabaseHas('ordered_products', [
            'id' => $orderedProduct->id,
            'product_count' => 3,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 7,
        ]);
    }

    public function test_admin_cannot_increase_ordered_product_quantity_when_stock_is_insufficient(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 0,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $orderedProduct = OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        $response = $this->actingAs($admin)->patch(
            "/admin/orders/{$order->id}/products/{$orderedProduct->id}/increase"
        );

        $response->assertRedirect(route('admin.orders.index'));

        $response->assertSessionHas('error');

        $this->assertDatabaseHas('ordered_products', [
            'id' => $orderedProduct->id,
            'product_count' => 2,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 0,
        ]);
    }

    public function test_admin_can_decrease_ordered_product_quantity(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $orderedProduct = OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        $response = $this->actingAs($admin)->patch(
            "/admin/orders/{$order->id}/products/{$orderedProduct->id}/decrease"
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ordered_products', [
            'id' => $orderedProduct->id,
            'product_count' => 1,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 9,
        ]);
    }

    public function test_admin_cannot_decrease_ordered_product_quantity_below_one(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $orderedProduct = OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 1,
            'unit_price_at_purchase' => 1.50,
        ]);

        $response = $this->actingAs($admin)->patch(
            "/admin/orders/{$order->id}/products/{$orderedProduct->id}/decrease"
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('ordered_products', [
            'id' => $orderedProduct->id,
            'product_count' => 1,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_admin_can_delete_product_from_order_and_restore_stock(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $secondProduct = Product::create([
            'name' => 'Pear',
            'image' => 'pear.png',
            'unit' => 'piece',
            'stock_quantity' => 5,
            'unit_price' => 2.00,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $orderedProduct = OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $secondProduct->id,
            'product_count' => 1,
            'unit_price_at_purchase' => 2.00,
        ]);

        $response = $this->actingAs($admin)->delete(
            "/admin/orders/{$order->id}/products/{$orderedProduct->id}"
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('ordered_products', [
            'id' => $orderedProduct->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 10,
        ]);
    }

    public function test_admin_cannot_delete_last_product_from_order(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $orderedProduct = OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        $response = $this->actingAs($admin)->delete(
            "/admin/orders/{$order->id}/products/{$orderedProduct->id}"
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('ordered_products', [
            'id' => $orderedProduct->id,
            'product_count' => 2,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_admin_cannot_delete_product_from_delivered_order(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $secondProduct = Product::create([
            'name' => 'Pear',
            'image' => 'pear.png',
            'unit' => 'piece',
            'stock_quantity' => 5,
            'unit_price' => 2.00,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'delivered',
        ]);

        $orderedProduct = OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $secondProduct->id,
            'product_count' => 1,
            'unit_price_at_purchase' => 2.00,
        ]);

        $response = $this->actingAs($admin)->delete(
            "/admin/orders/{$order->id}/products/{$orderedProduct->id}"
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('ordered_products', [
            'id' => $orderedProduct->id,
            'product_count' => 2,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_admin_can_add_product_to_order_and_reduce_stock(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/orders/{$order->id}/products",
            [
                'product_id' => $product->id,
            ]
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ordered_products', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 1,
            'unit_price_at_purchase' => 1.50,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 7,
        ]);
    }

    public function test_admin_can_add_existing_product_to_order(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $orderedProduct = OrderedProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_count' => 2,
            'unit_price_at_purchase' => 1.50,
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/orders/{$order->id}/products",
            [
                'product_id' => $product->id,
            ]
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ordered_products', [
            'id' => $orderedProduct->id,
            'product_count' => 3,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 7,
        ]);

        $this->assertDatabaseCount('ordered_products', 1);
    }

    public function test_admin_cannot_add_product_when_stock_is_empty(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 0,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/orders/{$order->id}/products",
            [
                'product_id' => $product->id,
            ]
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('ordered_products', [
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 0,
        ]);
    }

    public function test_admin_cannot_add_product_to_cancelled_order(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/orders/{$order->id}/products",
            [
                'product_id' => $product->id,
            ]
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('ordered_products', [
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_admin_cannot_add_product_to_delivered_order(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'delivered',
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/orders/{$order->id}/products",
            [
                'product_id' => $product->id,
            ]
        );

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('ordered_products', [
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_operator_cannot_update_order_status_through_admin_route(): void
    {
        $operator = User::factory()->create([
            'role' => 'operator',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $response = $this->actingAs($operator)->patch(
            "/admin/orders/{$order->id}/status",
            [
                'status' => 'cancelled',
            ]
        );

        $response->assertForbidden();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'new',
        ]);
    }


}
