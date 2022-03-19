<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\AccountType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_create_new_client()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $account_type = AccountType::factory()->create();
        $this->actingAs($user);

        $this->json('POST', route('admin.create.client'), [
            'first_name' => 'adeola',
            'last_name' => 'remi',
            'email' => 'client1@gmail.com',
            'password' => 'password',
            'amount' => 2000,
            'account_type' => 'savings'
        ])
        ->assertStatus(201);
    }
}
