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

        try {
            $winningSlot = random_int(1, $slots);

            $winner = new GameWinner;
            $winner->game_id = $game->id;
            $winner->position = $winningSlot;
            $winner->amount = ($game->amount * $game->odd) * 0.9;

            if($winner->save()) {
                $game->winner_id = $winner->id;
                $game->save();
            }
        } catch (\Exception $e) {
            exit();
        }
    }
}
