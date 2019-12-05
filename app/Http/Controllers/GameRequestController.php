<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Service\GameRequestService;
use Illuminate\Http\Request;

class GameRequestController extends Controller
{
    //
    protected $gameRequestService;


    public function __construct(GameRequestService $gameRequestService)
    {
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
