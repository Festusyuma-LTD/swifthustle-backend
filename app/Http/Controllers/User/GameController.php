<?php

namespace App\Http\Controllers\User;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Service\Game\Game;
use App\Service\Game\GameList;
use App\Service\Game\GameRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller{

    private $gameRequestService;
    private $gameService;
    private $gameListService;

    public function __construct(GameRequestService $gameRequestService){
        $this->gameRequestService = $gameRequestService;
        $this->gameListService = new GameList();
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

    public function getActiveGames() {
        $games = $this->gameListService->getUserActiveGames();

        if($games) {
            return ResponseHelper::success('success', $games);
        }else return ResponseHelper::forbidden('Forbidden');
    }

    public function getPastGames() {
        $games = $this->gameListService->getUserPastGames();

        if($games) {
            return ResponseHelper::success('success', $games);
        }else return ResponseHelper::forbidden('Forbidden');
    }

    public function getWonGames() {
        $games = $this->gameListService->getUserWonGames();

        if($games) {
            return ResponseHelper::success('success', $games);
        }else return ResponseHelper::forbidden('Forbidden');
    }
}
