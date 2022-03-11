<?php

use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use App\Models\TransactionHistory;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Resources\TransactionHistoryResource;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth/sign-in', LoginController::class);

// Route::get('hist', function () {
//     $user = User::find(2);
//     // $user_account = $user->userAccounts()->where('account_type_id', 1)->first();
//     // return $user_account->transactionHistories()->get();
//     return TransactionHistoryResource::collection($user->histories()->where('account_type_id', 2)->get());
// });


Route::middleware(['auth:sanctum'])->group(function(){
    //Admin
    Route::group(['prefix' => 'admin', 'middleware' => ['admin'], 'as' => 'admin.'], function(){
        Route::post('users', RegisterController::class);
        Route::post('account-types', [App\Http\Controllers\Admin\AccountTypeController::class, 'createAccountType']);
        Route::post('users/add-account-type/{user}', [App\Http\Controllers\Admin\ClientController::class, 'addNewAccountType']);
    });
    
    // Client
    Route::group(['prefix' => 'user', 'as' => 'users.'], function(){
        Route::get('/balance', [App\Http\Controllers\Client\ClientController::class, 'retrieveActBalance']);
        Route::post('/transfer', [App\Http\Controllers\Client\ClientController::class, 'transferMoney']);
        Route::get('/histories', [App\Http\Controllers\Client\ClientController::class, 'checkTransactionHistories']);

    });
});
