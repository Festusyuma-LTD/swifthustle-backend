<?php


namespace App\Repository;

use App\ValidGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidGameRepository {

    protected $validGame;

    public function __construct(ValidGame $validGame)
    {
        $this->validGame = $validGame;
    }

    public function add(Request $request) {

        return ValidGame::create([
           'amount' => $request->get('amount'),
            'odd' => $request->get('odd'),
            'active' => 0,
            'user_id'=> Auth::user()->id
        ]);
    }

    public function makeGameActiveOrInactive(Request $request)
    {
        $game = ValidGame::where('id', $request->id)->first();
        if($game) {
            $game->active = $request->active;
            $game->save();
            return $game;
        }
    }
}
