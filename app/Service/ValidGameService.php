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
            'odd' => 'required',
            'active' => 'required'
        ]);

        if($validGame->fails()) {
            return response()->json(['status' => 430, 'message' => 'operation failed', 'data' => $validGame->messages()->all()]);
        } else {
            $game = $this->validGameRepository->add($request);
            if ($game) {
                return response()->json(['status' => 200, 'message' => 'operation successful', 'data' => $game]);
            } else {
                return response()->json(['status' => 400, 'message' => 'operation failed']);
            }
        }
    }
}
