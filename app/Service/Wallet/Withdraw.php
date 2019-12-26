<?php


namespace App\Service\Wallet;


use App\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Withdraw{

    public $errors;

    public function __construct(){
        $this->errors = array();
    }

    public function index() {
        return Auth::user()->withdrawals;
    }

    public function withdraw(Request $request) {

        if ($this->validateAmount($request->amount)) {
            if ($this->debitUser($request->amount)) {
                $withdraw = new Withdrawal();
                $withdraw->amount = $request->amount;
                $withdraw->first_name = $request->firstName;
                $withdraw->last_name = $request->lastName;
                $withdraw->bank = $request->bank;
                $withdraw->account_number = $request->accountNumber;
                $withdraw->user_id = Auth::id();

                return $withdraw->save();
            }else return false;
        }else return false;
    }

    private function validateAmount($amount) {

        $wallet = Auth::user()->wallet;

        if ($wallet->wallet < $amount) {
            array_push($this->errors, 'insufficient funds');
            return false;
        }else return true;
    }

    private function debitUser($amount) {

        $wallet = Auth::user()->wallet;
        $wallet->wallet -= $amount;

        if ($wallet->save()) {
            return true;
        }else {
            array_push($this->errors, 'error occurred debiting user');
            return false;
        }
    }
}
