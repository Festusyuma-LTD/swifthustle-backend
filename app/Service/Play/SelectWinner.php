<?php


namespace App\Service\Play;


use App\GameRequest;
use App\Repository\GameRepository;
use App\Repository\GameRequestRepository;

class SelectWinner{

    private $gameRepository;
    private $gameRequestRepository;
    private $slotService;

    public function __construct(){
        $this->gameRepository = new GameRepository;
        $this->gameRequestRepository = new GameRequestRepository;
        $this->slotService = new Slot;
    }

    public function __invoke(){
        // TODO: Implement __invoke() method.
        $this->selectWinners();
    }

    private function selectWinners() {

        $expiredGames = $this->gameRepository->getExpiredGames();

        foreach ($expiredGames as $game) {
            $availableSlots = $this->slotService->getAvailableSlots($game->id);
            if (sizeof($availableSlots) > 0) {
                $gamePendingRequest = $this->gameRequestRepository->getGamePendingRequest($game->id);
                $this->autoAssignPositions($availableSlots, $gamePendingRequest);
            }

            $winner = $this->gameRepository->getWinner($game->id);
            $winnerRequest = $this->gameRequestRepository->getGameRequestPosition($game->id, $winner->position);
            $winner->user_id = $winnerRequest->user->id;
            $winner->save();
            //todo credit user wallet
        }
    }

    private function autoAssignPositions($availableSlots, $pendingRequests) {
        $count = sizeof($availableSlots) - 1;

        foreach ($pendingRequests as $pendingRequest) {

            $randomPosition = mt_rand(0, $count);
            $pendingRequest->position = $availableSlots[$randomPosition];

            if($pendingRequest->save()) {
                unset($availableSlots[$randomPosition]);
                $availableSlots = array_values($availableSlots);
                $count--;
            }
        }
    }
}
