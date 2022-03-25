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

    private function actingAsClient() {
        $client = User::factory()->create([
            'role' => 'client',
            'email' => 'client@gmail.com'
        ]);
        $this->actingAs($client);

        return $client;
    }

    public function test_client_can_retrive_balance()
    {
        $user = $this->actingAsClient();

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

    public function test_user_did_not_have_the_selected_account_type_to_check_act_balance()
    {
        $client =  $this->actingAsClient();

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
        $client =  $this->actingAsClient();

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


    public function test_user_can_transfer_money_to_different_account_no()
    {
        $client = $this->actingAsClient();

        $account_type = AccountType::factory()->create();

        $client->userAccounts()->create([
            'account_type_id' => $account_type->id,
            'balance' => 2000,
            'account_no' => 2234567890
        ]);

        $receiver = User::factory()->create([
            'role' => 'client',
            'email' => 'client2@client.com'
        ]);

        $receiver->userAccounts()->create([
            'account_type_id' => $account_type->id,
            'balance' => 3000,
            'account_no' => 1234567890
        ]);

        $response = $this->json('post', route('users.transfer.money'), [
            'account_type' => 'savings',
            'destination_act_no' => 1234567890,
            'amount' => 1900
        ]);
        $response->assertStatus(200);

    }

    public function test_user_cannot_transfer_money_to_the_same_account_no()
    {
        $client = $this->actingAsClient();

        $account_type = AccountType::factory()->create();

        $client->userAccounts()->create([
            'account_type_id' => $account_type->id,
            'balance' => 20000,
            'account_no' => 2234567890
        ]);

        $receiver = User::factory()->create([
            'role' => 'client',
            'email' => 'client2@client.com'
        ]);

        $receiver->userAccounts()->create([
            'account_type_id' => $account_type->id,
            'balance' => 3000,
            'account_no' => 1234567890
        ]);

        $response = $this->json('post', route('users.transfer.money'), [
            'account_type' => 'savings',
            'destination_act_no' => 2234567890,
            'amount' => 1000
        ]);
        // dd($response->getContent());
        $response->assertStatus(400);

    }
}
