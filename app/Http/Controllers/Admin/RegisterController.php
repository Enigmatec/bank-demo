<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Services\GenerateAccountNo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    private $generate_act_no;
    
    public function __construct(GenerateAccountNo $generate_act_no)
    {
        $this->generate_act_no = $generate_act_no;
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $user_account = $request->validate([
            'amount' => ['required', 'numeric'],
            'account_type' => ['required', 'exists:account_types,name'],
        ]);
        $account_type_id = AccountType::getAccountTypeID($user_account['account_type']);

        $array = \DB::transaction(function () use ($request, $account_type_id, $user_account) {
            
            //Create New Client
            $user = User::create($request->validated());

            // Create New User Account Type
            $user_account = $user->userAccounts()->create([
                'account_type_id' => $account_type_id,
                'account_no' => $this->generate_act_no->generateAccountNo(),
                'balance' => $user_account['amount']
            ]);

            return $user;
        });

        return response()->json([
            "success" => true,
            "message" => "New Client Created Successfully",
            "user" => UserResource::make($array)
        ], 201);
    }
}
