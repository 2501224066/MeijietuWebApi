<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SystemSetting
 *
 * @property string $setting_name 设定名称
 * @property string $value 设定值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereSettingName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereValue($value)
 * @mixin \Eloquent
 * @property int $id
 * @property string $about 解释
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereId($value)
 */
class SystemSetting extends Model
{
    protected $table = "system_setting";
}

