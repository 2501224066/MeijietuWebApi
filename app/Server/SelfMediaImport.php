<?php


namespace App\Server;


use App\Models\Attr\Filed;
use App\Models\Attr\Industry;
use App\Models\Attr\Modular;
use App\Models\Attr\Platform;
use App\Models\Attr\Region;
use App\Models\Attr\Theme;
use App\Models\Attr\Weightlevel;
use App\Models\Data\Goods;
use App\Models\User;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class SelfMediaImport
{
    public static function OP($uid, $excel_path, $modular_id, $theme_id)
    {
        // 转存在本地
        $arr  = explode('/', $excel_path);
        $name = $arr[count($arr) - 1];
        Storage::disk('public')->put($name, Storage::get($excel_path));
        $path = storage_path('app/public/') . $name;

        // 解析表格
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($path);
        foreach ($reader->getSheetIterator() as $k => $sheet) {
            // 只解析第一张表
            if ($k != 1) {
                continue;
            }

            foreach ($sheet->getRowIterator() as $kk => $row) {
                $rowArr = $row->toArray();

                try {
                    // 验证标签值
                    if (($kk == 1) && (!isset($rowArr[1])) ($rowArr[2] != 'TOKEN-MJT')) {
                        Log::info('【批量入驻】 ' . $excel_path . '非模板文件');
                        break;
                    }

                    // 从第四行开始读取
                    if ($kk < 4) {
                        continue;
                    }

                    // 超出504行停止
                    if ($kk > 504) {
                        $reader->close(); // 释放内存
                        break;
                    }

                    // 所有项目必填
                    if ((count($rowArr) != 13)
                        && (isset($rowArr[0]))
                        && (isset($rowArr[1]))
                        && (isset($rowArr[2]))
                        && (isset($rowArr[3]))
                        && (isset($rowArr[4]))
                        && (isset($rowArr[5]))
                        && (isset($rowArr[6]))
                        && (isset($rowArr[7]))
                        && (isset($rowArr[8]))
                        && (isset($rowArr[9]))
                        && (isset($rowArr[10]))
                        && (isset($rowArr[11]))
                        && (isset($rowArr[12]))) {
                        continue;
                    }

                    // 根据标题检查商品重复性
                    Goods::banRepeatGoods($rowArr[0]);

                    // 组装数据
                    $arr                 = [];
                    $arr['title']        = htmlspecialchars($rowArr[0]);
                    $arr['html_title']   = htmlspecialchars($rowArr[0]);
                    $arr['title_about']  = htmlspecialchars($rowArr[1]);
                    $arr['fans_num']     = htmlspecialchars($rowArr[2]);
                    $arr['qq_ID']        = htmlspecialchars($rowArr[4]);
                    $arr['modular_id']   = htmlspecialchars($modular_id);
                    $arr['modular_name'] = Modular::whereModularId($modular_id)->value('modular_name');
                    if ($rowArr[5] == '是') {
                        $arr['theme_id']   = 8;
                        $arr['theme_name'] = Theme::whereThemeId(8)->value('theme_name');
                    } else {
                        $arr['theme_id']   = 7;
                        $arr['theme_name'] = Theme::whereThemeId(7)->value('theme_name');
                    }

                    $region = Region::whereRegionName(htmlspecialchars($rowArr[6]))->first();
                    if ($region) {
                        $arr['region_name'] = $region->region_name;
                        $arr['region_id']   = $region->region_id;
                    } else {
                        $arr['region_name'] = '全国';
                        $arr['region_id']   = 1;
                    }

                    $filed = Filed::whereFiledName(htmlspecialchars($rowArr[7]))->first();
                    if ($filed) {
                        $arr['filed_id']   = $filed->filed_id;
                        $arr['filed_name'] = $filed->filed_name;
                    } else {
                        $arr['filed_id']   = 42;
                        $arr['filed_name'] = '其他';
                    }

                    $platform = Platform::wherePlatformName(htmlspecialchars($rowArr[8]))->first();
                    if ($platform) {
                        $arr['platform_id']   = $platform->platform_id;
                        $arr['platform_name'] = $platform->platform_name;
                    } else {
                        $arr['platform_id']   = 17;
                        $arr['platform_name'] = '其他';
                    }

                    $arr['reserve_status'] = htmlspecialchars($rowArr[9]) == '是' ? 1 : 0;

                    if (strpos($rowArr[10], 'http') == 0) {
                        $arr['link'] = htmlspecialchars($rowArr[10]);
                    } else {
                        continue;
                    }

                    if (strpos($rowArr[11], 'http') == 0) {
                        $arr['case_link'] = htmlspecialchars($rowArr[11]);
                    } else {
                        continue;
                    }

                    $arr['remarks'] = htmlspecialchars($rowArr[12]);

                    $arr['avatar_url'] = User::whereUid($uid)->value('head_portrait');

                    $arr['uid'] = $uid;

                    $arr['goods_num'] = createNum('GOODS');

                    $priceArr = ['26' => htmlspecialchars($rowArr[3])];

                    Goods::add($arr, $priceArr);

                } catch (Exception $e) {
                    Log::info('【商品】 批量入驻错误 ' . $e->getMessage());
                    continue;
                }

                $reader->close(); // 释放内存
                unlink($path); //删除文件
            }
        }
    }
}