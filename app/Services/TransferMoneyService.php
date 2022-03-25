<?php

namespace App\Services;

use App\Models\AccountType;
use App\Models\UserAccount;
use App\Models\TransactionHistory;

class TransferMoneyService {

    // private $user_account, $account_type, $transaction_history;
    // private $account_type_id  = null;

    // public function __construct(
    //     AccountType $account_type, 
    //     UserAccount $user_account,
    //     TransactionHistory $transaction_history
    //     )
    // {
    //     $this->account_type = $account_type;
    //     $this->user_account = $user_account;
    //     $this->transaction_history = $transaction_history;
    // }

    public function transferMoney($form_data)
    {
        $user = auth()->user();

        // getting sender's data
        $sender_data = $this->userBalance($form_data);

        // getting receiver data 
        $receiver_data = UserAccount::where('account_no', $form_data['destination_act_no'])->first();
        
        // check if user is sending to the same account no
        if($sender_data['account_no'] === $receiver_data['account_no'])
            return 'same account';

        // deduct the transfer amount from sender's balance
        $deduct_transfer_money  = $sender_data['balance'] - $form_data['amount']; 

        // add the transfer amount to receiver's balance
        $add_transfer_money  = $receiver_data['balance'] + $form_data['amount']; 

        // rollback all records to their inital data if any error occur within the transaction
        $transfered_amount = \DB::transaction(function () use ($user, $deduct_transfer_money, $add_transfer_money, $sender_data, $receiver_data, $form_data) {
            //Creating sender's transaction 
            $sender_data->sender()->create([
                'user_account_id' => $sender_data['id'],
                'receiver_id' => $receiver_data['user_id'],
                'account_type_id' => $sender_data['account_type_id'],
                'transfer_amount' => $form_data['amount']
            ]);

            //Creating receiver's transaction 
            TransactionHistory::create([
                'user_account_id' => $receiver_data['id'],
                'receiver_id' =>  $receiver_data['user_id'],
                'sender_id' => $sender_data['user_id'],
                'account_type_id' => $receiver_data['account_type_id'],
                'received_amount' => $form_data['amount']
            ]);
            
            // Update sender's Account
            $this->updateSenderBalance($sender_data, $deduct_transfer_money);
            
            //update receiver's account
            $this->updateReceiverBalance($receiver_data, $add_transfer_money, $form_data);
           
            return $form_data['amount'];
        });
        
        return $transfered_amount;
    }

    public function userBalance($form_data)
    {
        $user = auth()->user();
        $account_type_id = AccountType::getAccountTypeID($form_data['account_type']);
        return $user->userAccounts()->where('account_type_id', $account_type_id)
                ->where('balance', '>', $form_data['amount'])
                ->first();
    }

    public function checkIfClientIsSendingToTheSameActNo($sender_data, $form_data)
    {
        $receiver_data = UserAccount::where('account_no', $form_data['destination_act_no'])->first();
        if($sender_data['account_no'] === $receiver_data['account_no'])
            return 'same account';
    }

    public function updateReceiverBalance($receiver_data, $add_transfer_money, $form_data)
    {
        return $receiver_data->where('account_no', $form_data['destination_act_no'])->update([
            'balance' => $add_transfer_money
        ]);
    }

    public function updateSenderBalance($sender_data, $deduct_transfer_money)
    {
        return $sender_data->where('account_no', $sender_data['account_no'])->update([
            'balance' => $deduct_transfer_money
        ]);
    }

}