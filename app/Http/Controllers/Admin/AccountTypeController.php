<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountTypeController extends Controller
{
    public function createAccountType(Request $request)
    {
        $account_data = $request->validate([
            'name' => ['required', 'string', 'unique:account_types,name']
        ]);

        $account = AccountType::create($account_data);

        return response()->json([
            "success" => true,
            "message" => "New Account Type Added"
        ], 201);
    }
}
