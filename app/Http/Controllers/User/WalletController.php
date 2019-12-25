<?php

namespace App\Http\Controllers\User;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Service\Wallet\DepositService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller{

    private $depositService;

    public function __construct(){
        $this->middleware(['auth:api', 'isUser']);
        $this->depositService = new DepositService;
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
}
