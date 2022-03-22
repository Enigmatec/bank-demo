<?php

use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
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

Route::post('auth/sign-in', LoginController::class)->name('auth.login');

Route::middleware(['auth:sanctum'])->group(function(){
    //Admin
    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin'], 'as' => 'admin.'], function(){
        Route::post('users', RegisterController::class)->name('create.client');
        Route::post('account-types', [App\Http\Controllers\Admin\AccountTypeController::class, 'createAccountType'])->name('create.accountType');
        Route::post('users/add-account-type/{user}', [App\Http\Controllers\Admin\ClientController::class, 'addNewAccountTypeForUser'])->name('addActTypeForUser');
    });
    
    // Client
    Route::group(['prefix' => 'user', 'middleware' => ['role:client'], 'as' => 'users.'], function(){
        Route::get('/balance', [App\Http\Controllers\Client\ClientController::class, 'retrieveActBalance'])->name('retrieve.balance');
        Route::post('/transfer', [App\Http\Controllers\Client\ClientController::class, 'transferMoney'])->name('transfer.money');
        Route::get('/histories', [App\Http\Controllers\Client\ClientController::class, 'checkTransactionHistories'])->name('transaction.histories');

    });
});
