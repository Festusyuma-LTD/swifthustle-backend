<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidGame extends Model
{
    //
    protected $fillable = [
     'amount', 'odd', 'active', 'user_id'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
