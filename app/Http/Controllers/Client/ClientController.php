<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\AccountType;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\DB;
use App\Services\GenerateAccountNo;
use App\Http\Controllers\Controller;
use App\Services\TransferMoneyService;
use App\Http\Requests\TransferMoneyRequest;
use App\Http\Resources\TransactionHistoryResource;

class ClientController extends Controller
{
    
    public function retrieveActBalance(Request $request)
    {
        $user = auth()->user();
        $account_type = $request->validate(['account_type' => ['required', 'exists:account_types,name']]);
        $account_type_id = AccountType::getAccountTypeID($account_type['account_type']);

        $users_balance = $user->userAccounts()->select(['balance'])->where('account_type_id', $account_type_id)->first();
        if(! $users_balance)
            return response()->json(["success" => false, "message" => "User did not have such account type"], 400);
        
        return response()->json([
            "success" => true,
            "balance" => $users_balance
        ]);
    }

    public function transferMoney(TransferMoneyRequest $request, TransferMoneyService $transfer_money)
    {
        $form_data = $request->validated();

        //checking if users has the selected act type and also if users has enough balance
        if(is_null($transfer_money->userBalance($form_data))){
            return response()->json([
                "message" => "User Account Balance Too Low Or you dont have the selected account type"
            ], 400);
        }

        //process the transfer and check if client is sending to the same account number
        $data = $transfer_money->transferMoney($form_data);
        if($data === "same account") {
            return response()->json([
                "success" => false,
                "message" => "You can't transfer to the same account number",
            ], 400);
        }
        
        return response()->json([
            "success" => true,
            "message" => "Transfer Successful",
            "transfered" => $data
        ]);
    }

    public function checkTransactionHistories(Request $request)
    {
        $user = auth()->user();
       
        $account_type = $request->validate([
            'account_type' => ['required', 'exists:account_types,name']
        ]);
        
        $account_type_id = AccountType::getAccountTypeID($account_type);
        $user_account = $user->userAccounts()->where('account_type_id', $account_type_id)->first();
        if(! $user_account) {
            return response()->json([
                "success" => false,
                "message" =>"User does not have selected account type"
            ]);
        }
        $transaction_histories = $user_account->transactionHistories()->get();
        return response()->json([
            "success" => true,
            "histories" => TransactionHistoryResource::collection($transaction_histories)
        ]);
        
        
    }
}
