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
        $user = Auth::user();
        $tranx = $this->verify_transaction($reference);

        if ($tranx) {
            $transc = $this->transactionRepository->findByReference($tranx->data->reference);
            
            if ($transc) {
                if ($transc->paid == true) {
                    return response()->json(['status' => 400, 'message' => 'paid already']);
                }
            } else {
                $transc = $this->new_transaction($tranx, $user);
            }

            if ($tranx->data->status == 'success') {
                $transc->successful = true;
                $transc->paid = true;
                $transc->save();
                
                $wallet = $this->deposit($tranx->data->amount);
                return response()->json(['status' => 200, 'message' => $tranx->data->gateway_response, 'data' => $wallet]);
            } else {
                return response()->json(['status' => 400, 'message' => $tranx->data->gateway_response]);
            }
        }
        
    }

    public function new_transaction($tranx, $user) {
        $transaction = new Transaction;

        $transaction->reference = $tranx->data->reference;
        $transaction->amount = $tranx->data->amount;
        $transaction->successful = false;
        $transaction->paid = false;
        $transaction->user_id = $user->id;

        $transaction->save();
        return $transaction;
    }

    public function deposit($amount) {
        $user = Auth::user();
        $userWallet = $user->wallet;

        if ($userWallet) {
            $prevAmt = $userWallet->wallet;
            $newAmt = $prevAmt + $this->convertToNaira($amount);

            $userWallet->wallet = $newAmt;
            $userWallet->save();

            return $userWallet;
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
