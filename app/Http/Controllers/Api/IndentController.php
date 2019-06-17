<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Indent as IndentRequests;

class IndentController extends BaseController
{
    public static function createIndent(IndentRequests $request)
    {
        $info = json_decode($request->info);
        dd($info);
    }
}