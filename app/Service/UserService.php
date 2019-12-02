<?php

namespace App\Service;
use App\Http\Requests\UserRequest;
use App\Repository\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;


class UserService {

    protected $userRepository;

    public function __construct(UserRepository $user)
    {
        $this->userRepository = $user;
    }

    public function make(Request $request) {

        $validateDate = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_no' => 'required|string|max:20|unique:users'
        ]);

        if($validateDate->fails()) {
            return response()->json(['status' => 430, 'message' => 'operation failed', 'data' => $validateDate->messages()->all()]);
        } else {
            $user =$this->userRepository->add($request);
            if($user) {
                return response()->json(['status' => 200, 'message' => 'operation successful', 'data' => $user]);
            } else {
                return response()->json(['status' => 200, 'message' => 'operation failed']);
            }
        }

    }

    public function login(Request $request) {
        $validateData = Validator::make($request->all(), [
           'email' => 'required|email|string',
           'password' => 'required|string'
        ]);

        if($validateData->fails()) {
            return response()->json(['status' => 430, 'message' => 'operation failed', 'data' => $validateData->messages()->all()]);
        } else {
            if (!Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized'
                ], 401);
            } else {
                $user = $this->userRepository->authenticateUser($request);
                if ($user) {
                    return response()->json(['status' => 200, 'message' => 'operation successful', 'data' => $user]);
                }
            }
        }
    }

}
