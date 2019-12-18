<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helper\ResponseHelper;
use App\Service\Wallet\DepositService;

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
            return ResponseHelper::success('Success', $userWallet->wallet);
        } else return ResponseHelper::forbidden('Forbidden');
    }

    public function fundWallet(Request $request) {
        return ResponseHelper::success('Success', $this->depositService->make_transaction($request->reference));
    }
}
