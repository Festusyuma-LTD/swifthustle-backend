<?php

namespace App\Http\Controllers;

use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helper\ResponseHelper;
use App\Service\Wallet\DepositService;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    protected $depositService;

    public function __construct(DepositService $depositService)
    {
        $this->middleware(['auth:api','isUser']);
        $this->depositService = $depositService;
    }

    public function show() {
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
