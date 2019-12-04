<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpseclib\Math\BigInteger;

/**
 * @property double amount
 * @property int odd
 * @property BigInteger game_id
 */

class GameRequest extends Model{

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function game() {
        return $this->belongsTo('App\Game');
    }
}
