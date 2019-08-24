<?php


namespace App\Http\Controllers\v1;

use App\Models\Log\LogUpload;
use App\Server\File;
use App\Http\Requests\File as FileRequest;

class FileController extends BaseController
{
    /**
     * 图片上传
     * @param FileRequest $request
     * @return mixed
     */
    public function uploadImg(FileRequest $request)
    {
        $request = $request->all();

        $img = $request['image'];
        $upload_type = htmlspecialchars($request['upload_type']);
        // 检查上传类型
        File::checkUploadType($upload_type);
        // 检查格式
        File::checkExt($img, 'img_ext');
        // 检查大小
        File::checkSize($img, 'img_size');
        // 图片上传
        $path = File::uploadImg($img, $upload_type);
        // 记录
        LogUpload::add($path, $upload_type);

        return $this->success(['path' =>  $path]);
    }

    /**
     * 文件上传
     * @param FileRequest $request
     * @return mixed
     */
    public function uploadFile(FileRequest $request)
    {
        $request = $request->all();

        $file = $request['file'];
        $upload_type = htmlspecialchars($request['upload_type']);
        // 检查上传类型
        File::checkUploadType($upload_type);
        // 检查格式
        File::checkExt($file, 'file_ext');
        // 检查大小
        File::checkSize($file, 'file_size');
        // 文件上传
        $path = File::uploadFile($file, $upload_type);
        // 记录
        LogUpload::add($path, $upload_type);

        return $this->success(['path' =>  $path]);
    }
}