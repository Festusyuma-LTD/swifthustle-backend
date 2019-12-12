<?php

namespace App\Http\Controllers\User;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Service\Game\Game;
use App\Service\Game\GameRequestService;
use Illuminate\Http\Request;

class GameController extends Controller{

    private $gameRequestService;
    private $gameService;

    public function __construct(GameRequestService $gameRequestService){
        $this->middleware(['auth:api', 'isUser']);
        $this->gameRequestService = $gameRequestService;
        $this->gameService = new Game;
    }

    public function index($id) {
        $gameDetails = $this->gameService->gameDetails($id);

        if ($gameDetails) {
            return ResponseHelper::success('Success', $gameDetails);
        }else return ResponseHelper::forbidden('Forbidden');
    }

    public function joinGame(Request $request) {
        $game = $this->gameRequestService->joinActiveGame($request);

        if(!$game) {
            return ResponseHelper::forbidden('Operation forbidden', $game);
        } else if (is_array($game)) {
            return ResponseHelper::responseDisplay(400, 'Operation fail', $game);
        }else return ResponseHelper::success('Operation successful', $game);
    }
}
