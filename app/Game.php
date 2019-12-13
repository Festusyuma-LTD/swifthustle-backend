<?php

namespace App;

use Carbon\Traits\Date;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use phpseclib\Math\BigInteger;

/**
 * @property  int available_slots
 * @property  int odd
 * @property  double amount
 * @property  BigInteger id
 * @property  DateTime|null play_time
 * @property  DateTime|null expiration_time
 * @property  BigInteger|null winner_id
 */


class Game extends Model{

    public function winner() {
        return $this->hasOne('App\GameWinner');
    }

    public function players() {
        return $this->hasMany('App\GameRequest');
    }
}
