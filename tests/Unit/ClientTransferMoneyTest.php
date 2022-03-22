<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\AccountType;
use App\Services\TransferMoneyService;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ClientTransferMoneyTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_if_client_has_enough_to_transfer()
    {
        $client = User::factory()->create([
            'role' => 'client',
            'email' => 'client23@gmail.com'
        ]);
        $this->actingAs($client);

        $account_type = AccountType::factory()->create();
        $client->userAccounts()->create([
            'balance' => 2000,
            'account_type_id' => $account_type->id,
            'account_no' => 1234567890
        ]);

        $formdata = [
            'account_type' => $account_type->name,
            'amount' => 1000
        ];

        $check_balance = (new TransferMoneyService())->userBalance($formdata);

        $this->assertIsObject($check_balance);
    }

    public function test_check_if_client_does_not_have_enough_to_transfer()
    {
        $client = User::factory()->create([
            'role' => 'client',
            'email' => 'client23@gmail.com'
        ]);
        $this->actingAs($client);

        $account_type = AccountType::factory()->create();
        $client->userAccounts()->create([
            'balance' => 2000,
            'account_type_id' => $account_type->id,
            'account_no' => 1234567890
        ]);

        $formdata = [
            'account_type' => $account_type->name,
            'amount' => 4000
        ];

        $check_balance = (new TransferMoneyService())->userBalance($formdata);

        $this->assertNull($check_balance);
    }

    public function test_if_client_is_sending_money_to_the_same_account()
    {
        
    }
}
