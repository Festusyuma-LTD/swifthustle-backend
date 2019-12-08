<?php


namespace App\Repository;

use App\GameRequest;

class GameRequestRepository{

    public function getPendingRequest(){
        return GameRequest::where('game_id', null)->get();
    }

    public function getLimitedPendingRequest($limit){
        return GameRequest::where('game_id', null)->take($limit)->get();
    }
}
