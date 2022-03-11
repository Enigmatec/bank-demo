<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;


class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        $credentials = $request->validated();

        if(! Auth::attempt($credentials)) {
            return response()->json([
                "status" => false,
                "message" => "Invalid Login Credentials",
            ], 403);
        }

        $user = auth()->user();
        $token = $user->createToken($user->first_name)->plainTextToken;
        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
