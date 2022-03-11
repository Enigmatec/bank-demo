<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the users for the UserAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function accountTypes()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function sender()
    {
        return $this->hasMany(TransactionHistory::class, 'sender_id');
    }


    public function receiver()
    {
        return $this->hasMany(TransactionHistory::class, 'receiver_id');
    }

    public function transactionHistories()
    {
        return $this->hasMany(TransactionHistory::class, 'user_account_id');
    }
}


