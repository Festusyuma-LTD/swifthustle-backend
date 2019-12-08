<?php

namespace App\Service\Play;

use App\Game;
use App\GameWinner;
use App\Repository\GameRepository;

class PlayGame{

    private $gameRepository;

    public function __construct(){
        $this->gameRepository = new GameRepository;
    }

    public function __invoke(){
        // TODO: Implement __invoke() method.
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

        } catch (\Exception $e) {
            exit();
        }
    }
}
