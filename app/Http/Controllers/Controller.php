<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    public function sentSuccessResponse($data = '', $message = 'success', $status = 200)
    {
        return \response()->json([
            'data' => $data,
            'message' => $message,
        ], $status);
    }
}
