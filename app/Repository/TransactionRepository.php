<?php
namespace App\Repository;


use App\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionRepository{

    public function findByReference($reference) {
        return Transaction::where('reference', $reference)->first();
    }
}
