<?php


namespace App\Repository;

use App\GameRequest;
use Illuminate\Support\Facades\Auth;

class GameRequestRepository{

    public function getPendingRequest(){
        return GameRequest::where('game_id', null)->get();
    }

    public function getLimitedPendingRequest($limit){
        return GameRequest::where('game_id', null)->take($limit)->get();
    }

    public function getGamePendingRequest($id) {
        return GameRequest::where([
            ['game_id', $id],
            ['position', null]
        ])->get();
    }

    public function getGameRequestPosition($gameId, $position) {
        return GameRequest::where([
            ['game_id', $gameId],
            ['position', $position]
        ])->first();
    }

    public function getUserRequestGameIds() {
        return GameRequest::where('user_id', Auth::id())->whereNotNull('game_id')->pluck('game_id')->toArray();
    }
}
