<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpseclib\Math\BigInteger;

/**
 * @property BigInteger user_id
 * @property BigInteger game_id
 * @property int position
 * @property double amount
 * @property BigInteger id
 */
class GameWinner extends Model{

    public function game() {
        return $this->hasOne('App\Game');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
