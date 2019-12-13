<?php
namespace App\Service\Game;


use App\Repository\GameRepository;
use App\Repository\GameRequestRepository;
use Illuminate\Support\Facades\Auth;

class GameList{

    private $gameRepository;
    private $gameRequestRepository;

    public function __construct(){
        $this->gameRepository = new GameRepository;
        $this->gameRequestRepository = new GameRequestRepository;
    }

    public function getUserActiveGames() {
        try {
            $userGameIds = $this->getUserGameIds();
            return $this->gameRepository->getUserActiveGames($userGameIds);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUserPastGames() {
        try {
            $userGameIds = $this->getUserGameIds();
            return $this->gameRepository->getUserPastGames($userGameIds);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUserWonGames() {
        try {
            return $this->gameRepository->getUserWonGames();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getUserGameIds() {
        return $this->gameRequestRepository->getUserRequestGameIds();
    }
}
