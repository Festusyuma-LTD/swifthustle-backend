<?php

namespace App\Service;

use App\Repository\ValidGameRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidGameService {

    protected $validGameRepository;

    public function __construct(ValidGameRepository $validGameRepository)
    {
        $this->validGameRepository = $validGameRepository;
    }

    public function makeGame(Request $request) {

        $validGame = Validator::make($request->all(), [
           'amount' => 'required',
            'odd' => 'required'
        ]);

        if($validGame->fails()) {
            return $validGame->messages()->all();
        } else {
            $game = $this->validGameRepository->add($request);
            if ($game) {
                return $game;
            }
        }
    }

    public function isGameValid(Request $request) {
        $validGame = Validator::make($request->all(), [
            'id' => 'required',
            'active' => 'required|boolean'
        ]);

        if ($validGame->fails()) {
            return $validGame->messages()->all();
        } else {
            $game = $this->validGameRepository->makeGameActiveOrInactive($request);
            if ($game) {
                return $game;
            }
        }
    }
}
