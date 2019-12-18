<?php

namespace App\Service;
use App\Http\Requests\UserRequest;
use App\Mail\ResetUserPassword;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\ResetPassword;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;


class UserService {

    protected $userRepository;

    public function __construct(UserRepository $user, WalletRepository $walletRepository)
    {
        $this->userRepository = $user;
        $this->walletRepository = $walletRepository;
    }

    public function make(Request $request) {

        $validateDate = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_no' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6|same:password'
        ]);

        if($validateDate->fails()) {
            return $validateDate->messages()->all();
        } else {
            $user = $this->userRepository->add($request);
            if($user) {
                return $user;
            }
        }

    }

    public function createUserWallet($user) {
        $isUserValid = $this->userRepository->isUserValid($user->id);
        $userWallet = $this->walletRepository->findByUserId($user->id);

        if ($isUserValid) {
            if ($userWallet->isEmpty()) {
                $wallet = new Wallet;

                $wallet->wallet = 0;
                $wallet->bonus = 0;
                $wallet->user_id = $user->id;
    
                if ($wallet->save()) {
                    return $wallet;
                } else return false;
            } else return false;
        } else return false;
    }

    public function login(Request $request) {
        $validateData = Validator::make($request->all(), [
           'email' => 'required|email|string',
           'password' => 'required|string'
        ]);

        if($validateData->fails()) {
            return $validateData->messages()->all();
        } else {
            if (!Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                return 'UnAuthorized';
            } else {
                $user = $this->userRepository->authenticateUser($request);
                if ($user) {
                    return $user;
                }
            }
        }
    }

    public function forgetPassword(Request $request)
    {
        $user = $this->userRepository->resetPassword($request);

        if ($user) {
            $resetPassword = new ResetPassword();
            $resetPassword->remember_token = $request->remember_token = Str::random(40);
            $user->resetPassword()->save($resetPassword);

             Mail::to($request->get('email'))->send(new ResetUserPassword($request));
             return $user;
        }

    }


}
