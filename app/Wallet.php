<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpseclib\Math\BigInteger;

/**
 * @property double wallet
 * @property double bonus
 * @property BigInteger user_id
 */
class Wallet extends Model
{
    protected $fillable = [
        'wallet', 'bonus', 'user_id'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
