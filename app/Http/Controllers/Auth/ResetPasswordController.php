<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetUserPassword;
use App\Service\UserService;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    protected $userService;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    function resetPassword(Request $request){
        $user = $this->userService->forgetPassword($request);

        if($user) {
            return response()->json([
                "status" => 200,
                "message" => "Mail successfully sent, check the provided email address"
            ]);
        }else {
            return response()->json([
                "status" => 400,
                "message" => "User not found"
            ]);
        }
    }

    function resetPasswordUrl($token) {
        return $token;
    }
}
