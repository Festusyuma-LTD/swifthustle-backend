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

        try {
            $userAvailableSlots = $this->gameRepository->getUserAvailableSlots($request->id);
            if($userAvailableSlots->count() > 0) {
                $userSlot = $userAvailableSlots->first();
                dd($userSlot);
            }else return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
