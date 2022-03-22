<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\AccountType;
use App\Models\UserAccount;
use App\Services\GenerateAccountNo;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;
    

    public function test_only_admin_can_create_new_client()
    {
        // $this->withoutExceptionHandling();

        $admin = User::factory()->create([
            'role' => 'admin'
        ]);
        $this->actingAs($admin, 'sanctum');

        $account_type = AccountType::factory()->make();

        $user = [
            'first_name' => 'adeola',
            'last_name' => 'remi',
            'email' => 'client12@gmail.com',
            'password' => 'password',
            'role' => 'client',
            'amount' => 2000,
            'account_no' => 123456789,
            'account_type' => $account_type->name
        ];
    

        $response = $this->json('post', route('admin.create.client'), $user);
        $response->assertCreated();
    }

    public function test_non_admin_cannot_create_client()
    {
        $this->withoutExceptionHandling();
        $client = User::factory()->create([
            'role' => 'client'
        ]);
        $account_type = AccountType::factory()->create([
            'name' => 'current'
        ]);
        $this->actingAs($client);

        $response = $this->json('POST', route('admin.create.client'), [
            'first_name' => 'adeola',
            'last_name' => 'remi',
            'email' => 'client1@gmail.com',
            'password' => 'password',
            'amount' => 2000,
            'account_type' => $account_type->name
        ])
        ->assertStatus(401);
    }
}
