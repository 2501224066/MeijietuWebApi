<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Service\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('image');
        $ext = $file->getClientOriginalExtension();
        File::checkExt($ext);

    }
}