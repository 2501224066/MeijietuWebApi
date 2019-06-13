<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\Indent as IndentRequests;
use App\Models\Indent\IndentInfo;
use App\Models\Indent\IndentItem;

class IndentController extends BaseController
{
    /**
     * 生成营销订单
     */
    public function createMarketIndent(IndentRequests $request)
    {
        $indentItems = json_decode($request->indent_items_json);
        foreach ($indentItems as &$indentItem) {
            // 验证子项目信息
            IndentItem::checkIndentItem($indentItem);
            //return createIndentNnm('Market');
        }
        // 生成
        IndentInfo::add($indentItems);

        dd($indentItems);

    }
}