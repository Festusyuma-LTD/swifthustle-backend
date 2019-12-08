<?php

namespace App\Helper;

class ResponseHelper {



    public static function responseDisplay($status, $message, $data = null) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }


}
