<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\UserAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getCreatedatAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y.m.d H:i:s');
    }

    /**
     * Get the user that owns the TransactionHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function userAccounts()
    {
        return $this->belongsTo(UserAccount::class, 'user_account_id');
    }
}
