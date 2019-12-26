<?php

namespace App\Http\Controllers\User;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Service\Wallet\DepositService;
use App\Service\Wallet\Withdraw as WithdrawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller{

    private $depositService;
    private $withdrawService;

    public function __construct(){
        $this->middleware(['auth:api', 'isUser']);
        $this->depositService = new DepositService;
        $this->withdrawService = new WithdrawService;
    }

    public function index() {
        $user = Auth::user();
        $userWallet = $user->wallet;
        if ($userWallet) {
            return ResponseHelper::success('Success', $userWallet);
        } else return ResponseHelper::forbidden('Forbidden');
    }

    public function fundWallet(Request $request) {

        $validate = Validator::make($request->all(), [
            'reference' => 'required',
        ]);

        if (!$validate->fails()) {
            $fund = $this->depositService->makeTransaction($request->reference);

            if ($fund) {
                return ResponseHelper::success('Success', $fund);
            }else return ResponseHelper::forbidden('Forbidden', $this->depositService->errors);
        }else return ResponseHelper::badRequest('Bad request', $validate->errors()->all());
    }

    public function withdraw(Request $request) {

        $validate = Validator::make($request->all(), [
            'amount'        => 'required',
            'bank'          => 'required',
            'accountNumber' => 'required',
            'firstName'     => 'required',
            'lastName'     => 'required',
        ]);

        if (!$validate->fails()) {

            $withdraw =  $this->withdrawService->withdraw($request);
            if ($withdraw) {
                return ResponseHelper::success('success', $withdraw);
            }else return ResponseHelper::forbidden('Forbidden', $this->withdrawService->errors);
        }else return ResponseHelper::badRequest('Bad request', $validate->errors()->all());
    }

    public function withdrawals() {

        $withdrawals = $this->withdrawService->index();

        if ($withdrawals) {
            return ResponseHelper::success('success', $withdrawals);
        }else return ResponseHelper::forbidden('forbidden');
    }
}
