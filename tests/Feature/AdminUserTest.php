<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertViewIs('pages.admin.users.index');
        $response->assertViewHas('users');

        $response->assertSee($customer->name);
    }

    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'customer',
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.users.update', $user), [
                'name' => 'New Name',
                'surname' => 'Smith',
                'username' => $user->username,
                'email' => $user->email,
                'role' => 'customer',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'role' => 'customer',
        ]);
    }

    public function test_customer_cannot_update_user(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $anotherUser = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)
            ->patch(route('admin.users.update', $anotherUser), [
                'name' => 'Changed Name',
                'surname' => 'Smith',
                'username' => $anotherUser->username,
                'email' => $anotherUser->email,
                'role' => 'customer',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $anotherUser->id,
            'name' => $anotherUser->name,
        ]);
    }

    
}