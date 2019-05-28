<?php


namespace App\Service;


use App\Models\SystemSetting;
use Mockery\Exception;
use Illuminate\Support\Facades\Storage;

class File
{
    // 检查图片格式
    public static function checkImgExt($img)
    {
        $ext = $img->getClientOriginalExtension();
        $extArr = explode(',', SystemSetting::whereSettingName('img_ext')->value('value')); // 合法图片格式

        if (!in_array($ext, $extArr))
            throw new Exception('图片格式不合规');

        return true;
    }

    // 检查上传类型
    public static function checkUploadType($upload_type)
    {
        if ( ! in_array($upload_type, array_keys(type("UPLOAD_TYPE")) ) )
            throw new Exception('上传类型不合规');

        return true;
    }

    // 检查图片大小
    public static function checkImgSize($img)
    {
        $maxSize = SystemSetting::whereSettingName('img_size')->value('value');
        if ($img->getClientSize() > $maxSize)
            throw new Exception('图片大小不合格');
    }

    // 图片上传
    public static function uploadImg($img, $upload_type)
    {
        $path = "images/".$upload_type."/". str_random(30) . "." . $img->guessExtension();
        $re = Storage::put($path, file_get_contents($img->getRealPath() ) );
        if ( ! $re)
            throw new Exception('上传失败');

        return $path;
    }

    // 删除图片
    public static function deleteImg($path)
    {
        $prefix = 'images/';

        if (Storage::exists($path))
            Storage::delete($prefix . $path);

        return true;
    }

}