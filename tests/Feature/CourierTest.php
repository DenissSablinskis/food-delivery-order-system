<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierTest extends TestCase
{
    use RefreshDatabase;

    public function test_courier_can_start_delivery_assigned_order(): void
    {
        $courier = User::factory()->create([
            'role' => 'courier',
        ]);

        $operator = User::factory()->create([
            'role' => 'operator',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'operator_id' => $operator->id,
            'delivery_person_id' => $courier->id,
            'delivery_address' => 'Test address',
            'status' => 'in_process',
        ]);

        $response = $this->actingAs($courier)
            ->patch("/courier/orders/{$order->id}/start-delivery");

        $response->assertRedirect(route('courier.orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'delivering',
        ]);
    }

    public function test_courier_cannot_start_delivery_assigned_to_another_courier(): void
    {
        $courier = User::factory()->create([
            'role' => 'courier',
        ]);

        $anotherCourier = User::factory()->create([
            'role' => 'courier',
        ]);

        $operator = User::factory()->create([
            'role' => 'operator',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'operator_id' => $operator->id,
            'delivery_person_id' => $anotherCourier->id,
            'delivery_address' => 'Test address',
            'status' => 'in_process',
        ]);

        $response = $this->actingAs($courier)
            ->patch("/courier/orders/{$order->id}/start-delivery");

        $response->assertForbidden();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'in_process',
            'delivery_person_id' => $anotherCourier->id,
        ]);
    }

    public function test_courier_can_complete_delivery(): void
    {
        $courier = User::factory()->create([
            'role' => 'courier',
        ]);

        $operator = User::factory()->create([
            'role' => 'operator',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'operator_id' => $operator->id,
            'delivery_person_id' => $courier->id,
            'delivery_address' => 'Test address',
            'status' => 'delivering',
        ]);

        $response = $this->actingAs($courier)
            ->patch("/courier/orders/{$order->id}/complete");

        $response->assertRedirect(route('courier.orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'delivered',
        ]);
    }
}
