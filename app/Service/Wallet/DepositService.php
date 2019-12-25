<?php


namespace App\Service\Wallet;
use App\Transaction;
use App\Repository\TransactionRepository;
use Illuminate\Support\Facades\Auth;

class DepositService{

    private $transactionRepository;
    public $errors;

    public function __construct() {
        $this->transactionRepository = new TransactionRepository;
        $this->errors = array();
    }

    public function makeTransaction($reference) {
        $user = Auth::user();
        $transaction = $this->verifyTransaction($reference);

        if ($transaction) {
            $foundTransaction = $this->transactionRepository->findByReference($transaction->data->reference);

            if ($foundTransaction) {
                if ($foundTransaction->paid) {
                    array_push($this->errors, 'user has been credited');
                    return false;
                }
            } else {
                $foundTransaction = $this->new_transaction($transaction, $user);
            }

            if ($transaction->data->status == 'success') {
                $foundTransaction->successful = true;
                $foundTransaction->save();

                $wallet = $this->deposit($transaction->data->amount);
                if ($wallet) {
                    $foundTransaction->paid = true;
                    return $foundTransaction->save();
                }else return false;
            } else {
                array_push($this->errors, $transaction->data->gateway_response);
                return false;
            }
        }else return false;

    }

    public function new_transaction($transaction, $user) {
        $newTransaction = new Transaction;

        $newTransaction->reference = $transaction->data->reference;
        $newTransaction->amount = $transaction->data->amount;
        $newTransaction->user_id = $user->id;

        if ($newTransaction->save()) {
            return $newTransaction;
        }else return false;
    }

    public function deposit($amount) {
        $user = Auth::user();
        $userWallet = $user->wallet;
        $userWallet->wallet += $this->convertToNaira($amount);

        if ($userWallet->save()) {
            return $userWallet;
        }else return false;
    }

    public function convertToNaira($amount) {
        return $amount / 100;
    }

    public function verifyTransaction($reference) {
        $curl = curl_init();
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
        $error = curl_error($curl);

        if($error){
            array_push($this->errors, $error);
            return false;
        }

        $transaction = json_decode($response);
        if (!$transaction->status) {
            array_push($this->errors, $transaction->message);
            return false;
        }else return $transaction;
    }
}
