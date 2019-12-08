<?php
namespace App\Repository;


use App\Game;
use App\ValidGame;

class GameRepository{

    public function getValidGames() {
        return ValidGame::all();
    }

    public function getValidGameById($id) {
        return ValidGame::find($id);
    }

    public function getOpenGames(){
        return Game::where('available_slots', '>', 0)->orderBy('amount')->orderBy('odd')->get();
    }

    public function getPendingGames() {
        return Game::whereNull('winner_id')->whereNotNull('play_time')->get();
    }

    public function getLimitedPendingGames($limit) {
        return Game::whereNull('winner_id')->whereNotNull('play_time')->take($limit)->get();
    }
}
