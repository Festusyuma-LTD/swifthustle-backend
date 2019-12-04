<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameWinner extends Model{

    public function game() {
        return $this->hasOne('App\Game');
    }
}
