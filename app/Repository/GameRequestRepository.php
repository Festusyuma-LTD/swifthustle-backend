<?php


namespace App\Repository;


use App\GameRequest;
use App\ValidGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameRequestRepository
{
    protected $gameRequest;

    public function __construct(GameRequest $gameRequest)
    {
        $this->gameRequest = $gameRequest;
    }

    public function setGame(Request $request) {
        $getValidGame  = ValidGame::where('id', $request->id)->first();
        $userId = Auth::user()->id;


        if ($getValidGame) {
            if ($getValidGame->active) {
                return GameRequest::create([
                   'amount' => $getValidGame->amount,
                    'odd' => $getValidGame->odd,
                    'user_id' => $userId,
                    'position' => 0
                ]);
            } else {
                return 'Inactive';
            }
        }
    }

}
