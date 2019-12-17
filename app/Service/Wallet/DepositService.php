<?php


namespace App\Service\Wallet;
use App\Transaction;
use App\Repository\TransactionRepository;
use Illuminate\Support\Facades\Auth;

class DepositService
{

    public function __construct(TransactionRepository $transactionRepository) {
        $this->transactionRepository = $transactionRepository;
    }

    public function make_transaction($reference) {
        $tranx = $this->verify_transaction($reference);
        if ($tranx) {
            if ($tranx->data->status == 'success') {
                $user = Auth::user();
                $transc = $this->transactionRepository->findByReference($tranx->data->reference);

                if ($transc->isEmpty()) {
                    $isSuccessful = true;
                    $isPaid = true;

                    $this->save_transaction($tranx, $isSuccessful, $isPaid, $user);
                    $wallet = $this->deposit($tranx->data->amount);
                    return response()->json(['status' => 200, 'message' => $tranx->data->gateway_response, 'data' => $wallet]);
                } else return response()->json(['status' => 400, 'message' => 'operation failed']);
            } else {
                $isSuccessful = false;
                $isPaid = false;

                $this->save_transaction($tranx, $isSuccessful, $isPaid, $user);
                return response()->json(['status' => 400, 'message' => $tranx->data->gateway_response]);
            }
        }
        
    }

    public function save_transaction($tranx, $isSuccessful, $isPaid, $user) {
        $transaction = new Transaction;

        $transaction->reference = $tranx->data->reference;
        $transaction->amount = $tranx->data->amount;
        $transaction->successful = $isSuccessful;
        $transaction->paid = $isPaid;
        $transaction->user_id = $user->id;

        $transaction->save();
    }

    public function deposit($amount) {
        $user = Auth::user();
        $userWallet = $user->wallet;

        if ($userWallet) {
            $prevAmt = $userWallet->wallet;
            $newAmt = $prevAmt + $this->convertToNaira($amount);

            $userWallet->wallet = $newAmt;
            $userWallet->wallet;
        }
    }

    public function convertToNaira($amount) {
        return $amount / 100;
    }

    public function verify_transaction($reference) {
        $curl = curl_init();
        if(!$reference){
            die('No reference supplied');
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

        if ($tranx->status == true) return $tranx;
        
    }
}
