<?php

namespace App\Service\Play;

use App\Game;
use App\GameWinner;
use App\Repository\GameRepository;
use App\Repository\GameRequestRepository;

class PlayGame{

    private $gameRepository;
    private $gameRequestRepository;

    public function __construct(){
        $this->gameRepository = new GameRepository;
        $this->gameRequestRepository = new GameRequestRepository;
    }

    public function __invoke(){
        $this->play();
    }

    private function play() {
        $pendingGames = $this->gameRepository->getPendingGames();

        foreach ($pendingGames as $game) {
            $this->getWinner($game);
        }
    }

    private function getWinner(Game $game) {

        $slots = $game->odd;
        $amount = $game->amount;

        try {
            $winningSlot = random_int(1, $slots);
            $gameWinner = $this->gameRequestRepository->getGameRequestPosition($game->id, $winningSlot);
            //$game = $gameWinner->user();
            dump($gameWinner);
        } catch (\Exception $e) {
            exit();
        }
    }
}
