<?php

namespace Tests\Unit;

use App\Models\AccountType;
use App\Models\UserAccount;
use Tests\TestCase;
use App\Services\GenerateAccountNo;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountNoTest extends TestCase
{
    use RefreshDatabase;

    function test_if_account_no_can_be_generated(){
        $account_no = (new GenerateAccountNo())->generateRandomNoOfTenLength();
        $this->assertIsNumeric($account_no);
    }

    public function test_if_account_no_is_ten_in_length()
    {
        $account_no = (new GenerateAccountNo())->generateRandomNoOfTenLength();
        $to_array = \str_split($account_no);
        $this->assertCount(10, $to_array);
    }
}
 