<?php


namespace App\Models\Indent;


use Illuminate\Database\Eloquent\Model;

class IndentInfo extends Model
{
    protected $table = 'indent_info';

    public $guarded = [];

    // 生成订单与订单子项目
    public static function add($indentItems)
    {
        // 插入订单信息
        self::insertGetId([
            'indent_num' => createIndentNnm('Market')
        ]);
    }
}