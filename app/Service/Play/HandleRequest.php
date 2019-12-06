<?php
namespace App\Service\Play;

set_time_limit(100);

use App\Game;
use App\GameRequest;
use App\Repository\GameRepository;

class HandleRequest{

    private $gameRepository;

    public function __construct(){
        $this->gameRepository = new GameRepository();
    }

    public function __invoke(){
        $this->handleRequest();
    }

    public function handleRequest() {
        $pendingRequest = $this->gameRepository->getLimitedPendingRequest(35000);
        $games = $this->gameRepository->getOpenGames();

        foreach ($pendingRequest as $request) {
            $notFound = true;

            foreach ($games as $key => $game) {
                if ($request->amount == $game->amount && $request->odd == $game->odd) {
                    $this->assignRequestToGame($request, $game);
                    if($game->available_slots <= 0) $games->pull($key);
                    $notFound = false;
                    break;
                }
            }

            if ($notFound) {
                $game = $this->createGame($request);
                if($game) {
                    $games->push($game);
                    $this->assignRequestToGame($request, $game);
                }
            }
        }
    }

    private function createGame(GameRequest $request) {

        $game = new Game();
        $game->amount = $request->amount;
        $game->odd = $request->odd;
        $game->available_slots = $request->odd;

        if ($game->save()) {
            return $game;
        }else return false;
    }

    private function assignRequestToGame(GameRequest $request, Game $game) {
        $request->game_id = $game->id;
        $game->available_slots--;

        if($request->save()) {
            $game->save();
        }
    }
}
