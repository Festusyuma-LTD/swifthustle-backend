<?php

namespace App\Http\Controllers\User;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Service\Play\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayController extends Controller{

    private $slotService;

    public function __construct(){
        $this->slotService = new Slot;
    }

    public function userGameSlots($id) {
        $gameSlots = $this->slotService->getUserSlots($id);

        if ($gameSlots) {
            return ResponseHelper::success('success', $gameSlots);
        }else return ResponseHelper::forbidden('Forbidden');
    }

    public function selectPosition(Request $request) {

        $validate = Validator::make($request->all(), [
            'id' => 'required|int',
            'position' => 'required|int',
        ]);

        if($validate->fails()) {
            return ResponseHelper::badRequest($validate->errors()->all());
        }else {
            $selectGame = $this->slotService->selectPosition($request);

            if($selectGame) {

            }else return ResponseHelper::forbidden('Forbidden');
        }
    }

    public function getPlayTime() {

    }

    public function getWinner() {

    }
}
