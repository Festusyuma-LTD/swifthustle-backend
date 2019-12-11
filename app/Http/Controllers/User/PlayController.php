<?php

namespace App\Http\Controllers\User;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Service\Play\Slot;
use App\Service\Play\Winner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayController extends Controller{

    private $slotService;
    private $winnerService;

    public function __construct(){
        $this->slotService = new Slot;
        $this->winnerService = new Winner();
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
            'slot' => 'required|int',
        ]);

        if($validate->fails()) {
            return ResponseHelper::badRequest($validate->errors()->all());
        }else {
            $selectGame = $this->slotService->selectPosition($request);
            if($selectGame) {
                return ResponseHelper::success('Success');
            }else return ResponseHelper::forbidden('Forbidden');
        }
    }

    public function getPlayTime($id) {

    }

    public function getWinner($id) {
        $winner = $this->winnerService->getWinner($id);

        if($winner) {
            return ResponseHelper::success('Success', $winner);
        }else return ResponseHelper::forbidden('Forbidden');
    }
}
