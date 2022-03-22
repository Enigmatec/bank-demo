<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_create_new_account_type()
    {
        $this->withoutExceptionHandling();
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);
        $this->actingAs($admin);

        $response = $this->json('POST', route('admin.create.accountType'), [
            'name' => 'savings'
        ])
        ->assertCreated();
    }

    public function test_non_admin_cannot_create_new_account_type()
    {
        $this->withoutExceptionHandling();
        $admin = User::factory()->create([
            'role' => 'client'
        ]);
        $this->actingAs($admin);

        $response = $this->json('POST', route('admin.create.accountType'), [
            'name' => 'current'
        ])
        ->assertStatus(401);
    }
}
