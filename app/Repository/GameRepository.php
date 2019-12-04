<?php
namespace App\Repository;


use App\Game;
use App\GameRequest;

class GameRepository{

    public function getPendingRequest(){
        return GameRequest::where('game_id', null)->get();
    }

    public function getLimitedPendingRequest($limit){
        return GameRequest::where('game_id', null)->take($limit)->get();
    }

    public function getOpenGames(){
        return Game::where('available_slots', '>', 0)->orderBy('amount')->orderBy('odd')->get();
    }
}
