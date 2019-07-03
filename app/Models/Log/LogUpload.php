<?php


namespace App\Models\Log;


use App\Service\Pub;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * App\Models\Log\LogUpload
 *
 * @property int $log_upload_id
 * @property int $uid 用户id
 * @property string $file 上传文件
 * @property string $upload_type 上传类型
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogUpload whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogUpload whereLogUploadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogUpload whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogUpload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogUpload whereUploadType($value)
 * @mixin \Eloquent
 */
class LogUpload extends Model
{
    protected $table = 'log_upload';

    protected $primaryKey = 'log_upload_id';

    public $guarded = [];

    // 添加记录
    public static function add($path, $upload_type)
    {
        self::create([
            'uid' => JWTAuth::user()->uid,
            'file' => $path,
            'upload_type' => array_flip(Pub::UPLOAD_TYPE)[$upload_type]
        ]);
    }
}