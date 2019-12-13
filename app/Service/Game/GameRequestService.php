<?php


namespace App\Service\Game;


use App\GameRequest;
use App\Repository\GameRepository;
use App\Repository\GameRequestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GameRequestService
{

    private $gameRequestRepository;
    private $gameRepository;

    public function __construct(){
        $this->gameRequestRepository = new GameRequestRepository;
        $this->gameRepository = new GameRepository;
    }

    public function joinActiveGame(Request $request){

        $joinGame = Validator::make($request->all(), ['id' => 'required']);

        if($joinGame->fails()) {
            return $joinGame->messages()->all();
        } else {
            //todo debit user wallet
            return $this->saveGameRequest($request);
        }
    }

    private function saveGameRequest(Request $request) {

        $validGame = $this->gameRepository->getValidGameById($request->id);
        $userId = Auth::id();

        if ($validGame && $validGame->active) {

            $gameRequest = new GameRequest;
            $gameRequest->amount = $validGame->amount;
            $gameRequest->odd = $validGame->odd;
            $gameRequest->user_id = $userId;

            if($gameRequest->save()) {
                return $gameRequest;
            } else return false;
        }return false;
    }

}
