<?php

namespace App\Services;

use App\Models\UserAccount;

class GenerateAccountNo 
{
    protected $user_account;

    public function generateRandomNoOfTenLength()
    {
        $account_no =  mt_rand(1000000000,9999999999);
        return $account_no;
    }   

    public function generateAccountNo()
    {
        if(UserAccount::where('account_no', $this->generateRandomNoOfTenLength())->exists()) 
            return $this->generateRandomNoOfTenLength();
        return $this->generateRandomNoOfTenLength();
    }

}
