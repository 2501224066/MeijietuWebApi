<?php


namespace App\Jobs;


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
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class  GoodsBatchAdd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uid;

    protected $excel_path;

    protected $modular_id;

    protected $theme_id;

    public function __construct($uid, $excel_path, $modular_id, $theme_id)
    {
        $this->uid        = $uid;
        $this->excel_path = $excel_path;
        $this->modular_id = $modular_id;
        $this->theme_id   = $theme_id;
    }

    public function handle()
    {
        $uid        = $this->uid;
        $excel_path = $this->excel_path;
        $modular_id = $this->modular_id;
        $theme_id   = $this->theme_id;

        // 转存在本地
        $arr  = explode('/', $excel_path);
        $name = $arr[count($arr) - 1];
        Storage::disk('public')->put($name, Storage::get($excel_path));
        $path = storage_path('app/public/') . $name;

        // 软文批量入驻
        if ((Modular::whereModularId($modular_id)->value('modular_name') == '软文营销')
            && (Theme::whereThemeId($theme_id)->value('theme_name') == '软文')) {

            // 解析Excel
            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open($path);
            try {
                foreach ($reader->getSheetIterator() as $k => $sheet) {
                    // 只解析第一张表
                    if ($k != 1) {
                        continue;
                    }

                    foreach ($sheet->getRowIterator() as $kk => $row) {
                        $rowArr = $row->toArray();

                        // 从第四行开始读取
                        if ($kk < 4) {
                            continue;
                        }

                        // 超出504行停止
                        if ($kk > 504) {
                            $reader->close(); // 释放内存
                            unlink($path); //删除文件
                            return true;
                        }

                        // 必填项目为空则跳过
                        if (!(($rowArr[0] !== null)
                            && ($rowArr[1] !== null)
                            && ($rowArr[2] !== null)
                            && ($rowArr[3] !== null)
                            && ($rowArr[4] !== null)
                            && ($rowArr[5] !== null)
                            && ($rowArr[6] !== null)
                            && ($rowArr[7] !== null)
                            && ($rowArr[8] !== null)
                            && ($rowArr[9] !== null)
                            && ($rowArr[10] !== null)
                            && ($rowArr[11] !== null)
                            && ($rowArr[12] !== null)
                            && ($rowArr[13] !== null)
                            && ($rowArr[14] !== null)
                            && ($rowArr[15] !== null)
                            && ($rowArr[17] !== null))) {
                            continue;
                        }

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

                        $arr['link'] = htmlspecialchars($rowArr[16]);

                        $arr['case_link'] = htmlspecialchars($rowArr[17]);

                        $arr['remarks'] = empty($rowArr[18]) ? null : htmlspecialchars($rowArr[18]);

                        $arr['avatar_url'] = User::whereUid($uid)->value('head_portrait');

                        $arr['uid'] = $uid;

                        $arr['goods_num'] = createNum('GOODS');

                        $priceArr = ['26' => htmlspecialchars($rowArr[2])];

                        Goods::add($arr, $priceArr);
                    }
                }

            } catch (Exception $e) {
                Log::info('【商品】 批量入驻错误 ' . $e->getMessage());
            }

            $reader->close(); // 释放内存
            unlink($path); //删除文件
        }
        return true;
    }
}