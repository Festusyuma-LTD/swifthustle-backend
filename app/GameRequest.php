<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameRequest extends Model
{
    //
    protected $fillable = [
      'amount', 'odd', 'position', 'user_id'
    ];

}
