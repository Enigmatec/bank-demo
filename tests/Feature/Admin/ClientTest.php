<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\AccountType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_account_type_for_client()
    {
        
        $this->withoutExceptionHandling();

        $admin = User::factory()->create([
            'role' => 'admin'
        ]);
        $this->actingAs($admin);

        $account_type = AccountType::factory()->make();

        $user = User::factory()->create([
            'email' => 'client1@gmail.com',
            'role' => 'client'
        ]);

        $response = $this->json('post', route("admin.addActTypeForUser", $user->id), [
                'amount' => 2000,
                'account_type' => $account_type->name
        ]);

        $response->assertCreated();
    }
}
