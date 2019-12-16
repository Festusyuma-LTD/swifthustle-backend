<?php


namespace App\Service\Wallet;
use App\Transaction;
use App\Repository\TransactionRepository;
use Illuminate\Support\Facades\Auth;

class DepositService
{

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function make_transaction($email, $reference) {
        return $this->verify_transaction($email, $reference);
    }

    public function deposit($amount) {
        $user = Auth::user();
        $userWallet = $user->wallet;

        if ($userWallet) {
            $prevAmt = $userWallet->wallet;
            $newAmt = $prevAmt + $this->convertToNaira($amount);

            $userWallet->wallet = $newAmt;
            return $userWallet->wallet;
        }
    }

    public function convertToNaira($amount) {
        return $amount / 100;
    }

    public function verify_transaction($email, $reference) {
        $curl = curl_init();
        if(!$reference){
            return 'No reference supplied';
        }

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer sk_test_8fb7cb28113ab96f85104e630d8350e14ff47379",
            "cache-control: no-cache"
        ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
        die('Curl returned error: ' . $err);
        }

        $tranx = json_decode($response);
        if(!$tranx->status){
        die('API returned error: ' . $tranx->message);
        }

        if('success' == $tranx->status){
            $user = Auth::user();
            $transc = $this->transactionRepository->findByReference($tranx->data->reference);

            if ($transc->isEmpty()) {
                if ($email == $user->email) {
                    $transaction = new Transaction;

                    $transaction->reference = $tranx->data->reference;
                    $transaction->amount = $tranx->data->amount;
                    $transaction->successful = true;
                    $transaction->paid = true;
                    $transaction->user_id = $user->id;

                    $transaction->save();
                    return $this->deposit($tranx->data->amount);

                } else {
                    $transaction = new Transaction;

                    $transaction->reference = $tranx->data->reference;
                    $transaction->amount = $tranx->data->amount;
                    $transaction->successful = false;
                    $transaction->paid = false;
                    $transaction->user_id = $user->id;

                    $transaction->save();
                }
            } else return "stud";
        }
    }
}
