<?php
namespace App\Repository;


use App\Wallet;
use Illuminate\Support\Facades\Auth;

class WalletRepository{

    public function findByUserId($id) {
        return Wallet::where('user_id', $id)->get();
    }
}
