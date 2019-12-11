<?php
namespace App\Service\Play;

use App\Game;
use App\Repository\GameRepository;
use App\Repository\GameRequestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Slot{

    private $gameRepository;
    private $gameRequestRepository;

    public function __construct(){
        $this->gameRepository = new GameRepository;
        $this->gameRequestRepository = new GameRequestRepository;
    }

    public function getUserSlots($id) {

        try {
            $userSlots = $this->gameRepository->getUserSlots($id);
            $userAvailableSlots  = $this->gameRepository->getUserAvailableSlots($id);

            return [
                'userSlots' => $userSlots,
                'userAvailableSlots' => $userAvailableSlots->count()
            ];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function selectPosition(Request $request) {

        $slot = $request->slot;
        $gameId = $request->id;

        try {
            $userAvailableSlots = $this->gameRepository->getUserAvailableSlots($gameId);
            if($userAvailableSlots->count() > 0) {
                $userSlot = $userAvailableSlots->first();
                $availableGameSlots = $this->getAvailableSlots($gameId);

                if(in_array($slot, $availableGameSlots)) {
                    $userSlot->position = $slot;
                    return $userSlot->save();
                }
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAvailableSlots($id) {
        $takenSlots = $this->gameRepository->getTakenSlots($id);
        $game = $this->gameRepository->find($id);
        $slots = range(1, $game->odd);

        return array_diff($slots, $takenSlots);
    }
}
