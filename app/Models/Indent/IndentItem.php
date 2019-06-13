<?php


namespace App\Models\Indent;


use App\Service\ModularData;
use Illuminate\Database\Eloquent\Model;

class IndentItem extends Model
{
    protected $table = 'indent_item';

    public $guarded = [];

    // 验证子项目信息
    public static function checkIndentItem($indentItem)
    {
        // 检查模块类型
        ModularData::checkModularType($indentItem->modular_type);
        // 检查商品是否存在
        ModularData::checkGoodsHas($indentItem->modular_type, $indentItem->goods_id);
        // 补充信息
        $goodsInfo                      = ModularData::modularTypeToGetGoodsTableClass($indentItem->modular_type)::whereGoodsId($indentItem->goods_id)
            ->first();
        $indentItem->goods_title        = $goodsInfo->goods_title;
        $indentItem->uid                = $goodsInfo->uid;
        $goodsInfoPrice                 = ModularData::modularTypeToGetGoodsPriceTableClass($indentItem->modular_type)::whereGoodsId($indentItem->goods_id)
            ->where('priceclassify_id', $indentItem->priceclassify_id)
            ->first();
        $indentItem->priceclassify_name = $goodsInfoPrice->priceclassify_name;
        $indentItem->price              = $goodsInfoPrice->price;
    }


}