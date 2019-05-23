<?php


namespace App\Service;


use Mockery\Exception;

class File
{
    //合法图片格式
    const EXTARR = ["png", "jpg", "jpeg"];

    public static function checkExt($ext)
    {
        if( ! in_array($ext, self::EXTARR))
           throw new Exception('格式不合规');
    }
}