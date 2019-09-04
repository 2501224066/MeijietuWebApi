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

class SoftArticleImport
{
    /**
     * 软文批量上传
     * @param string $uid 用户ID
     * @param string $excel_path 批量表格路径
     * @param string $modular_id 模块ID
     * @param static $theme_id 主题ID
     * @return bool
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Throwable
     */
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
                    if (($kk == 1) && (empty($rowArr[1])) && ($rowArr[1] != 'TOKEN-MJT')) {
                        Log::info('【批量入驻】 '.$excel_path.'非模板文件');
                        break;
                    }

                    // 从第四行开始读取
                    if ($kk < 4) {
                        continue;
                    }

                    // 超出504行停止
                    if ($kk > 504) {
                        break;
                    }

                    // 所有项目必填
                    if ((count($rowArr) != 19)
                        && (empty($rowArr[0]))
                        && (empty($rowArr[1]))
                        && (empty($rowArr[2]))
                        && (empty($rowArr[3]))
                        && (empty($rowArr[4]))
                        && (empty($rowArr[5]))
                        && (empty($rowArr[6]))
                        && (empty($rowArr[7]))
                        && (empty($rowArr[8]))
                        && (empty($rowArr[9]))
                        && (empty($rowArr[10]))
                        && (empty($rowArr[11]))
                        && (empty($rowArr[12]))
                        && (empty($rowArr[13]))
                        && (empty($rowArr[14]))
                        && (empty($rowArr[15]))
                        && (empty($rowArr[16]))
                        && (empty($rowArr[17]))
                        && (empty($rowArr[18]))) {
                        continue;
                    }

                    // 根据标题检查商品重复性
                    Goods::banRepeatGoods($rowArr[0]);

                    // 组装数据
                    $arr                 = [];
                    $arr['title']        = htmlspecialchars($rowArr[0]);
                    $arr['html_title']   = htmlspecialchars($rowArr[0]);
                    $arr['title_about']  = htmlspecialchars($rowArr[1]);
                    $arr['qq_ID']        = htmlspecialchars($rowArr[3]);
                    $arr['modular_id']   = htmlspecialchars($modular_id);
                    $arr['modular_name'] = Modular::whereModularId($modular_id)->value('modular_name');
                    $arr['theme_id']     = htmlspecialchars($theme_id);
                    $arr['theme_name']   = Theme::whereThemeId($theme_id)->value('theme_name');

                    $region = Region::whereRegionName(htmlspecialchars($rowArr[4]))->first();
                    if ($region) {
                        $arr['region_name'] = $region->region_name;
                        $arr['region_id']   = $region->region_id;
                    } else {
                        $arr['region_name'] = '全国';
                        $arr['region_id']   = 1;
                    }

                    $filed = Filed::whereFiledName(htmlspecialchars($rowArr[5]))->first();
                    if ($filed) {
                        $arr['filed_id']   = $filed->filed_id;
                        $arr['filed_name'] = $filed->filed_name;
                    } else {
                        $arr['filed_id']   = 42;
                        $arr['filed_name'] = '其他';
                    }

                    $platform = Platform::wherePlatformName(htmlspecialchars($rowArr[6]))->first();
                    if ($platform) {
                        $arr['platform_id']   = $platform->platform_id;
                        $arr['platform_name'] = $platform->platform_name;
                    } else {
                        $arr['platform_id']   = 17;
                        $arr['platform_name'] = '其他';
                    }

                    $industry = Industry::whereIndustryName(htmlspecialchars($rowArr[7]))->first();
                    if ($industry) {
                        $arr['industry_id']   = $industry->industry_id;
                        $arr['industry_name'] = $industry->industry_name;
                    } else {
                        $arr['industry_id']   = 1;
                        $arr['industry_name'] = '其他';
                    }

                    $arr['included_sataus'] = htmlspecialchars($rowArr[8]) == '是' ? 1 : 0;

                    switch (htmlspecialchars($rowArr[9])) {
                        case '没有入口':
                            $arr['entry_status'] = 1;
                            break;
                        case '首页入口':
                            $arr['entry_status'] = 2;
                            break;
                        case '频道入口':
                            $arr['entry_status'] = 3;
                            break;
                        case '上级入口':
                            $arr['entry_status'] = 4;
                            break;
                    }

                    $phone_weightlevel = Weightlevel::whereWeightlevelName(htmlspecialchars($rowArr[10]))->first();
                    if ($phone_weightlevel) {
                        $arr['phone_weightlevel_id']  = $phone_weightlevel->weightlevel_id;
                        $arr['phone_weightlevel_img'] = $phone_weightlevel->img_path;
                    } else {
                        $arr['phone_weightlevel_id']   = 0;
                        $arr['phone_weightlevel_imgs'] = 'images/currency_weightlevel_img/9e3e77fa9c6a959927876103c725991b.png';
                    }

                    $pc_weightlevel = Weightlevel::whereWeightlevelName(htmlspecialchars($rowArr[11]))->first();
                    if ($pc_weightlevel) {
                        $arr['pc_weightlevel_id']  = $pc_weightlevel->weightlevel_id;
                        $arr['pc_weightlevel_img'] = $pc_weightlevel->img_path;
                    } else {
                        $arr['pc_weightlevel_id']  = 0;
                        $arr['pc_weightlevel_img'] = 'images/currency_weightlevel_img/9e3e77fa9c6a959927876103c725991b.png';
                    }

                    $arr['news_source_status'] = htmlspecialchars($rowArr[12]) == '是' ? 1 : 0;

                    $arr['link_type'] = htmlspecialchars($rowArr[13]) == '是' ? 1 : 0;

                    $arr['weekend_status'] = htmlspecialchars($rowArr[14]) == '是' ? 1 : 0;

                    $arr['max_title_long'] = htmlspecialchars($rowArr[15]);

                    if (strpos($rowArr[16], 'http')) {
                        $arr['link'] = htmlspecialchars($rowArr[16]);
                    } else {
                        continue;
                    }

                    if (strpos($rowArr[17], 'http')) {
                        $arr['case_link'] = htmlspecialchars($rowArr[17]);
                    } else {
                        continue;
                    }

                    $arr['remarks'] = htmlspecialchars($rowArr[18]);

                    $arr['avatar_url'] = User::whereUid($uid)->value('head_portrait');

                    $arr['uid'] = $uid;

                    $arr['goods_num'] = createNum('GOODS');

                    $priceArr = ['26' => htmlspecialchars($rowArr[2])];

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