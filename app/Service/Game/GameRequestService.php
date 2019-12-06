<?php


namespace App\Service\Game;


use App\Repository\GameRequestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameRequestService
{

    protected $gameRequestRepository;

    public function __construct(GameRequestRepository $gameRequestRepository)
    {
        $this->gameRequestRepository = $gameRequestRepository;
    }

    public function joinActiveGame(Request $request){

        $joinGame = Validator::make($request->all(), [
           'id' => 'required'
        ]);

        if($joinGame->fails()) {
            return $joinGame->messages()->all();
        } else {
            $gameRequest = $this->gameRequestRepository->setGame($request);
            if($gameRequest) {
                return $gameRequest;
            }
        }
    }

}
