<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_delete_product_used_in_order(): void
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
            ->delete("/admin/products/{$product->id}");

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }

    public function test_admin_can_delete_product_not_used_in_order(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $response = $this->actingAs($admin)
            ->delete("/admin/products/{$product->id}");

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_customer_cannot_delete_product(): void
    {
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

        $response = $this->actingAs($customer)
            ->delete("/admin/products/{$product->id}");

        $response->assertForbidden();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }

    public function test_guest_cannot_delete_product(): void
    {
        $product = Product::create([
            'name' => 'Apple',
            'image' => 'apple.png',
            'unit' => 'piece',
            'stock_quantity' => 8,
            'unit_price' => 1.50,
        ]);

        $response = $this->delete("/admin/products/{$product->id}");

        $response->assertRedirect('/login');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }

    public function test_admin_cannot_delete_nonexistent_product(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->delete('/admin/products/99999');

        $response->assertNotFound();
    }

    public function test_admin_can_create_product(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Banana',
                'image' => UploadedFile::fake()->create(
                    'banana.jpg',
                    100,
                    'image/jpeg'
                ),
                'unit' => 'piece',
                'stock_quantity' => 10,
                'unit_price' => 1.25,
            ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Banana',
            'unit' => 'piece',
            'stock_quantity' => 10,
            'unit_price' => 1.25,
        ]);

        $product = Product::where('name', 'Banana')->firstOrFail();

        Storage::disk('public')->assertExists(
            str_replace('storage/', '', $product->image)
        );
    }

}
