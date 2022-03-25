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

    private function actingAsClient() {
        $client = User::factory()->create([
            'role' => 'client',
            'email' => 'client@gmail.com'
        ]);
        $this->actingAs($client);

        return $client;
    }

    public function test_check_if_client_has_enough_to_transfer()
    {
        
        $client = $this->actingAsClient();

        $account_type = AccountType::factory()->create([
            'name' => 'type6'
        ]);
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
        $client = $this->actingAsClient();

        $account_type = AccountType::factory()->create([
            'name' => 'type5'
        ]);
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

}
