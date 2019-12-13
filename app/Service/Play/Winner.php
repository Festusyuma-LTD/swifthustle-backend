<?php
namespace App\Service\Play;

use App\Repository\GameRepository;
use DateTime;

class Winner{

    private $gameRepository;

    public function __construct(){
        $this->gameRepository = new GameRepository;
    }

    public function getWinner($id) {

        try {
            $game = $this->gameRepository->find($id);
            $playTime = new DateTime($game->play_time);
            $now = new DateTime();

            if($now >= $playTime) {
                return [
                    'status' => 'ready',
                    'winner' => $game->winner->user,
                    'amount' => $game->winner->amount,
                ];
            }else {
                return [
                    'status' => 'waiting',
                    'timer' => ( $playTime->getTimestamp() - $now->getTimestamp() ),
                ];
            }
        }catch (\Exception $e) {
            return false;
        }
    }
}
