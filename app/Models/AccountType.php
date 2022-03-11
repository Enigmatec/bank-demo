<?php

namespace App\Models;

use App\Models\UserAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountType extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the comments for the AccountType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    
    
     /**
      * Get all of the comments for the AccountType
      *
      * @return \Illuminate\Database\Eloquent\Relations\HasMany
      */
     public function userAccounts()
     {
         return $this->hasMany(UserAccount::class, 'account_type_id');
     }

     public function scopeGetAccountTypeID($query, $type)
     {
         return $query->where('name', $type)->value('id');
     }
}
