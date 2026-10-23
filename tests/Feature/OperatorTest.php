<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_operator_can_start_processing_assigned_order(): void
    {
        $operator = User::factory()->create([
            'role' => 'operator',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'operator_id' => $operator->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $response = $this->actingAs($operator)
            ->patch("/operator/orders/{$order->id}/start-processing");

        $response->assertRedirect(route('operator.orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'in_process',
        ]);
    }

    public function test_operator_cannot_start_processing_order_assigned_to_another_operator(): void
    {
        $operator = User::factory()->create([
            'role' => 'operator',
        ]);

        $anotherOperator = User::factory()->create([
            'role' => 'operator',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'operator_id' => $anotherOperator->id,
            'delivery_address' => 'Test address',
            'status' => 'new',
        ]);

        $response = $this->actingAs($operator)
            ->patch("/operator/orders/{$order->id}/start-processing");

        $response->assertForbidden();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'new',
            'operator_id' => $anotherOperator->id,
        ]);
    }

    public function test_operator_cannot_start_processing_order_that_is_not_new(): void
    {
        $operator = User::factory()->create([
            'role' => 'operator',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'operator_id' => $operator->id,
            'delivery_address' => 'Test address',
            'status' => 'in_process',
        ]);

        $response = $this->actingAs($operator)
            ->patch("/operator/orders/{$order->id}/start-processing");

        $response->assertStatus(422);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'in_process',
        ]);
    }

    
}
