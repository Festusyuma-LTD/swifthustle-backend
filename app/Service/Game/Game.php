<?php
namespace App\Service\Game;

use App\Repository\GameRepository;
use App\Service\Play\Slot as SlotService;
use App\Service\Play\Winner as WinnerService;
use Symfony\Component\ErrorHandler\Error\FatalError;

class Game{

    private $slotService;
    private $winnerService;
    private $gameRepository;

    public function __construct(){
        $this->slotService = new SlotService;
        $this->winnerService = new WinnerService;
        $this->gameRepository = new GameRepository;
    }

    public function gameDetails($id) {

        try {
            $game = $this->gameRepository->find($id);
            $players = $this->getPlayers($id);
            $availableSlots = $this->slotService->getAvailableSlots($id);
            $winner = $this->winnerService->getWinner($id);

            return [
                'game' => $game,
                'players' => $players,
                'availableSlots' => $availableSlots,
                'winner' => $winner
            ];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getPlayers($id) {
        $players = $this->gameRepository->find($id);
        if($players) {
            $players = $players->players()->get();
        }else throw new \Exception('game not found');

        $playersFormatted = [];

        foreach ($players as $player) {
            $user = $player->user;

            array_push($playersFormatted, [
                'username' => $user->username,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'position' => $player->position
            ]);
        }

        return $playersFormatted;
    }
}
