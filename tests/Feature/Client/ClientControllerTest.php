<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\AccountType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_retrive_balance()
    {
        $user = User::factory()->create([
            'role' => 'client',
            'email' => 'client1@gmail.com'
        ]);
        $this->actingAs($user);

        $account_type = AccountType::factory()->create([
            'name' => 'type1'
        ]);

        $user->userAccounts()->create([
            'balance' => 2000,
            'account_type_id' => $account_type->id,
            'account_no' => 1234567890
        ]);


        $response = $this->json('GET', route('users.retrieve.balance'), [
            'account_type' => 'type1'
        ]);

        $response->assertOk();
    }

    public function test_user_did_not_have_the_selected_account_type()
    {
        $client = User::factory()->create([
            'role' => 'client',
            'email' => 'client@gmail.com'
        ]);
        $this->actingAs($client);

        $account_type = AccountType::factory()->create([
            'name' => 'type2'
        ]);

        $client->userAccounts()->create([
            'balance' => 2000,
            'account_type_id' => $account_type->id,
            'account_no' => 1234567890
        ]);


        $response = $this->json('GET', route('users.retrieve.balance'), [
            'account_type' => 'type1'
        ]);

        // dd($response->getContent());
        $response->assertStatus(400);
    }

    public function test_user_can_check_transaction_histories()
    {
        $client = User::factory()->create([
            'role' => 'client',
            'email' => 'client@gmail.com'
        ]);
        $this->actingAs($client);

        $account_type = AccountType::factory()->create([
            'name' => 'type3'
        ]);

        $client->userAccounts()->create([
            'balance' => 2000,
            'account_type_id' => $account_type->id,
            'account_no' => 1234567890
        ]);

        $response = $this->json('get', route('users.transaction.histories'), [
                'account_type' => 'type3'
        ]);

        $response->assertOk();


    }
}
