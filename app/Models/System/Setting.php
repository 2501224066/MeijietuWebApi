<?php


namespace App\Models\System;


use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\System\Setting
 *
 * @property int $id
 * @property string $setting_name 设定名称
 * @property string $about 解释
 * @property string|null $value 设定值
 * @property string|null $img 图片值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Setting whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Setting whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Setting whereSettingName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Setting whereValue($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    protected $table = "system_setting";

    // banner
    public static function banner()
    {
        return self::whereSettingName('banner_img')->get();
    }

    // staticUrl()
    public static function staticUrl()
    {
        return self::whereSettingName('staticUrl')->value('value');
    }
}

