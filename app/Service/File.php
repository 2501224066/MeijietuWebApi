<?php


namespace App\Service;


use App\Models\SystemSetting;
use Mockery\Exception;
use Illuminate\Support\Facades\Storage;

class File
{
    const IMG_PREFIX = 'images/';

    const FILE_PREFIX = 'file/';

    // 检查格式
    public static function checkExt($file, $key)
    {
        $ext    = $file->getClientOriginalExtension();
        $extArr = explode(',', SystemSetting::whereSettingName($key)->value('value'));

        if (!in_array($ext, $extArr))
            throw new Exception('文件格式不合规');

        return true;
    }

    // 检查上传类型
    public static function checkUploadType($upload_type)
    {
        if (!in_array($upload_type, array_keys(type("UPLOAD_TYPE"))))
            throw new Exception('上传类型不合规');

        return true;
    }

    // 检查大小
    public static function checkSize($img, $key)
    {
        $maxSize = SystemSetting::whereSettingName($key)->value('value');
        if ($img->getClientSize() > $maxSize)
            throw new Exception('文件大小不合规');
    }

    // 图片上传
    public static function uploadImg($img, $upload_type)
    {
        $path = self::IMG_PREFIX . $upload_type . "/" . str_random(30) . "." . $img->getClientOriginalExtension();
        $re   = Storage::put($path, file_get_contents($img->getRealPath()));
        if (!$re)
            throw new Exception('上传失败');

        return $path;
    }

    // 文件上传
    public static function uploadFile($file, $upload_type)
    {
        $path = self::FILE_PREFIX . $upload_type . "/" . str_random(30) . "." . $file->getClientOriginalExtension();
        $re   = Storage::put($path, file_get_contents($file->getRealPath()));
        if (!$re)
            throw new Exception('上传失败');

        return $path;
    }
}