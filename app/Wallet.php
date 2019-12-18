<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'wallet', 'bonus', 'user_id'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
