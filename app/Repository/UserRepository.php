<?php

namespace App\Repository;

use App\Http\Requests\UserRequest;
use App\Mail\ResetUserPassword;
use App\ResetPassword;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class UserRepository {

    protected $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function add(Request $request) {
        return User::create([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'role' => Auth::user() && Auth::user()->role === "1" ? 2: 3,
            'phone_no' => $request->get('phone_no'),
            'password' => Hash::make($request->get('password')),
        ]);
    }

    public function authenticateUser(Request $request) {
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function resetPassword(Request $request) {
         return User::where('email', $request->get('email'))->first();
    }

}
