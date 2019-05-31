<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Softarticle\Entryclassify
 *
 * @property int $entryclassify_id 入口分类id
 * @property string|null $entryclassify_name 入口分类名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Entryclassify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Entryclassify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Entryclassify query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Entryclassify whereEntryclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Entryclassify whereEntryclassifyName($value)
 * @mixin \Eloquent
 */
class Entryclassify extends Model
{
    protected $table = 'softarticle_entryclassify';

    protected $primaryKey = 'entryclassify_id';

    public $guarded = [];

    public $timestamps =false;
}