<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    use Helpers;

    public function success($parm = "success",  int $responseCode = 200)
    {
        $data = array();
        $message = "success";
        is_string($parm) ? $message = $parm :  $data = $parm;

        return $this->response->array([
            'status_code' => $responseCode,
            'message' => $message,
            'data' => $data
        ])->setStatusCode($responseCode);
    }
}