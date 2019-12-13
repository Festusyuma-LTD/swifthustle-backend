<?php
namespace App\Repository;


use App\Game;
use App\ValidGame;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameRepository{

    public function find($id) {
        return Game::find($id);
    }

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

    public function getExpiredGames() {
        return DB::table('games')->join('game_winners', 'games.winner_id', '=', 'game_winners.id')->whereNotNull('winner_id')->where([
            ['expiration_time', '<=', now()],
            ['game_winners.user_id', '=', null]
        ])->get();
    }

    public function getLimitedPendingGames($limit) {
        return Game::whereNull('winner_id')->whereNotNull('play_time')->take($limit)->get();
    }

    public function getUserSlots($id) {
        return Game::find($id)->players->where('user_id', Auth::id());
    }

    public function getUserAvailableSlots($id) {
        return Game::find($id)->players->where('user_id', Auth::id())->where('position', null);
    }

    public function getTakenSlots($id) {
        return Game::find($id)->players->where('position', '!=', null)->pluck('position')->toArray();
    }

    public function getWinner($id) {
        return Game::find($id)->winner;
    }

    public function getUserActiveGames($ids) {
        return Game::whereIn('id', $ids)->where('play_time', '>=', now())->get();
    }

    public function getUserPastGames($ids) {
        return Game::whereIn('id', $ids)->where('play_time', '<', now())->get();
    }

    public function getUserWonGames() {
        return DB::table('games')->join('game_winners', 'games.winner_id', '=', 'game_winners.id')->where('game_winners.user_id', Auth::id())->get();
    }
}
