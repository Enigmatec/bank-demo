<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Services\GenerateAccountNo;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{

    public function __construct(GenerateAccountNo $generate_act_no)
    {
        $this->generate_act_no = $generate_act_no;
    }

    public function addNewAccountTypeForUser(Request $request, User $user)
    {
        $user_account = $request->validate([
            'amount' => ['required', 'numeric'],
            'account_type' => ['required', 'exists:account_types,name'],
        ]);
        $account_type_id = AccountType::getAccountTypeID($user_account['account_type']);

        if($user->userAccounts()->where('account_type_id', $account_type_id)->exists())
            return response()->json(["success" => false, "message" => "Customer has this account type"]);

        $add_new_act_type =  $user->userAccounts()->create([
            'account_type_id' => $account_type_id,
            'account_no' => $this->generate_act_no->generateAccountNo(),
            'balance' => $user_account['amount']
        ]);

        return response()->json(["success" => true, "message" => "New Account Type Added For User"], 201);
    }
}
