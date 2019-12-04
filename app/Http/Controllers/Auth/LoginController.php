<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Service\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $userService;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('guest')->except('logout');
        $this->userService = $userService;
    }

    public function login(Request $request){
        $user = $this->userService->login($request);
        if (is_array($user)) {
              return response()->json(['status' => 430, 'message' => 'operation failed', 'data' => $user]);
        } else {
            if ($user === 'UnAuthorized') {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized'
                ], 401);
            } else {
                if ($user) {
                    return response()->json(['status' => 200, 'message' => 'operation successful', 'data' => $user]);
                }
            }
        }
    }
}
