<?php

namespace App\Services;

use App\Models\UserAccount;

class GenerateAccountNo 
{
    protected $user_account;

    public function __construct(UserAccount $user_account)
    {
        $this->user_account = $user_account;    
    }
    public function generateAccountNo()
    {
        $account_no =  mt_rand(1000000000,9999999999);
        if($this->user_account->where('account_no', $account_no)->exists()){
            $this->generateAccountNo();
        }
        return $account_no;
    }   
}
