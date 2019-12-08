<?php

namespace App\Http\Controllers\User;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Service\Game\GameRequestService;
use Illuminate\Http\Request;

class GameController extends Controller{

    private $gameRequestService;

    public function __construct(GameRequestService $gameRequestService){
        $this->middleware(['auth:api', 'isUser']);
        $this->gameRequestService = $gameRequestService;
    }

    public function joinGame(Request $request) {
        $game = $this->gameRequestService->joinActiveGame($request);
        if(is_array($game)) {
            return ResponseHelper::responseDisplay(400, 'operation fail', $game);
        }

        if ($game === 'Inactive') {
            return ResponseHelper::responseDisplay(400, 'Game is not active');
        } else {
            return ResponseHelper::responseDisplay(200, 'Operation successful', $game);
        }

    }
}
