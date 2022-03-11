<?php

namespace App\Services;

class AdminService {

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function ifUserHasActType($user)
    {
        return $user->userAccounts()->where('account_type_id', $account_type_id)->exists();
    }
}