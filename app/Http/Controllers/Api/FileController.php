<?php


namespace App\Http\Controllers\Api;

use App\Service\File;
use App\Http\Requests\File as FileRequest;
use Illuminate\Support\Facades\Storage;

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
        File::checkImgExt($img); // 检查图片格式
        File::checkUploadType($upload_type); //检查上传类型
        $path = File::uploadImg($img, $upload_type);

        return $this->success(['path' =>  $path]);
    }

}