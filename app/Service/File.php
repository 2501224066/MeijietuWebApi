<?php


namespace App\Service;


use App\Models\SystemSetting;
use Mockery\Exception;
use Illuminate\Support\Facades\Storage;

class File
{
    const UPLOAD_TYPE = [

    ];

    // 检查图片格式
    public static function checkImgExt($img)
    {
        $ext = $img->getClientOriginalExtension();
        $extArr = explode(',', SystemSetting::whereSettingName('img_ext')->value('value')); // 合法图片格式

        if (!in_array($ext, $extArr))
            throw new Exception('图片格式不合规');

        return true;
    }

    //检查上传类型
    public static function checkUploadType($upload_type)
    {
        if (!in_array($upload_type, self::UPLOAD_TYPE))
            throw new Exception('上传类型不合规');

        return true;
    }

    // 图片上传
    public static function uploadImg($img)
    {
        $path = "temporary/" . md5(uniqid()) . "." . $img->guessExtension();
        $re = Storage::put($path, $img);
        if (!$re)
            throw new Exception('上传失败');

        return $path;
    }

    // 删除图片
    public static function deleteImg($path)
    {
        $prefix = 'images/';

        if (Storage::get($path))
            Storage::delete($prefix . $path);

        return true;
    }

}