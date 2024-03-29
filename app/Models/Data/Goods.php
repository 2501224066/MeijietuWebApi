<?php


namespace App\Models\Data;

use App\Models\Attr\Filed;
use App\Models\Attr\Industry;
use App\Models\Attr\Modular;
use App\Models\Attr\Platform;
use App\Models\Attr\Priceclassify;
use App\Models\Attr\Region;
use App\Models\Attr\Theme;
use App\Models\Attr\Weightlevel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Data\Goods
 *
 * @property int $goods_id
 * @property int $uid 用户id 默认官方账户
 * @property string $goods_num 编码
 * @property string $title 标题
 * @property string $html_title 页面tile
 * @property string $title_about 标题简介
 * @property string|null $content 套餐内容
 * @property int|null $max_title_long 限制标题长度
 * @property string|null $avatar_url 头像
 * @property string|null $qrcode_url 二维码
 * @property string|null $qq_ID QQ号
 * @property string|null $weixin_ID 微信号
 * @property string|null $room_ID 房间号
 * @property int $auth_type 认证类型 0=未认证 1=已认证
 * @property int $news_source_status 是否新闻源 0=否 1=是
 * @property int $entry_status 入口状态 1=没有入口 2=首页入口 3=频道入口 4=上级入口
 * @property int $included_sataus 收录状态 0=不包收录 1=包收录
 * @property string|null $link 链接
 * @property string|null $case_link 案例链接
 * @property int $link_type 链接类型 0=不可带网址 1=可带网址
 * @property int $weekend_status 周末可发 0=否 1=是
 * @property int $reserve_status 是否预约 0=否 1=是
 * @property string|null $remarks 备注
 * @property int $modular_id
 * @property string $modular_name 模块
 * @property int $theme_id
 * @property string $theme_name 主题
 * @property int|null $filed_id
 * @property string|null $filed_name 领域
 * @property int|null $platform_id
 * @property string|null $platform_name 平台
 * @property string|null $platform_logo 平台logo
 * @property int|null $industry_id
 * @property string|null $industry_name 行业
 * @property int|null $region_id
 * @property string|null $region_name 地区
 * @property int|null $phone_weightlevel_id
 * @property string|null $phone_weightlevel_img 移动端权重 图片
 * @property int|null $pc_weightlevel_id
 * @property string|null $pc_weightlevel_img PC端权重 图片
 * @property string|null $verify_cause 审核原因
 * @property int $verify_status 审核状态 0=待审核 1=未通过 2=通过
 * @property int $status 上架状态 0=未上架 1=上架
 * @property int $recommend_status 推荐状态 0=否 1=是
 * @property int $delete_status 删除状态 0=未删除 1=删除
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $level_name 级别名称
 * @property int|null $fans_num 粉丝数
 * @property int|null $avg_read_num 平均阅读数
 * @property int|null $max_read_num 最大阅读数
 * @property int|null $total_like_num 合计点赞数
 * @property int|null $avg_like_num 平均点赞数
 * @property int|null $max_like_num 最大点赞数
 * @property int|null $total_comment_num 合计评论数
 * @property int|null $avg_comment_num 平均评论数
 * @property int|null $max_comment_num 最大评论数
 * @property int|null $total_retweet_num 合计转发数
 * @property int|null $avg_retweet_num 平均转发数
 * @property int|null $max_retweet_num 最大转发数
 * @property int|null $follows_num 关注数量
 * @property int|null $notes_num 笔记数量
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Data\GoodsPrice[] $goods_price
 * @property-read \App\Models\Data\GoodsPrice $one_goods_price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereAuthType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereAvgCommentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereAvgLikeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereAvgReadNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereAvgRetweetNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereCaseLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereDeleteStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereEntryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereFansNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereFiledName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereFollowsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereGoodsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereHtmlTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereIncludedSataus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereIndustryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereIndustryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereLevelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereLinkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereMaxCommentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereMaxLikeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereMaxReadNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereMaxRetweetNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereMaxTitleLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereModularId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereModularName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereNewsSourceStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereNotesNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods wherePcWeightlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods wherePcWeightlevelImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods wherePhoneWeightlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods wherePhoneWeightlevelImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods wherePlatformLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods wherePlatformName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereQrcodeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereRecommendStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereReserveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereRoomID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereTitleAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereTotalCommentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereTotalLikeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereTotalRetweetNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereVerifyCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereVerifyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereWeekendStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Goods whereWeixinID($value)
 * @mixin \Eloquent
 */
class Goods extends Model
{
    protected $table = 'data_goods';

    protected $primaryKey = 'goods_id';

    protected $guarded = [];

    const STATUS = ['下架' => 0, '上架' => 1];

    const VERIFY_STATUS = ['待审核' => 0, '未通过' => 1, '已通过' => 2];

    const RECOMMEND_STATUS = ['否' => 0, '是' => 1];

    const DELETE_STATUS = ['未删除' => 0, '已删除' => 1];

    public function goods_price(): HasMany
    {
        return $this->hasMany(GoodsPrice::class, 'goods_id', 'goods_id');
    }

    public function one_goods_price(): HasOne
    {
        return $this->hasOne(GoodsPrice::class, 'goods_id', 'goods_id');
    }

    // 推荐商品
    public static function recommendGoods()
    {
        // 模块及主题
        $mt = Modular::with('theme')->get();
        // 获取推荐
        $re = [];
        foreach ($mt as $m) {
            foreach ($m['theme'] as $t) {
                $re[$m['modular_name']][$t['theme_name']] = self::with('goods_price')
                    ->where('recommend_status', self::RECOMMEND_STATUS['是'])
                    ->where('status', self::STATUS['上架'])
                    ->where('verify_status', self::VERIFY_STATUS['已通过'])
                    ->where('delete_status', self::DELETE_STATUS['未删除'])
                    ->where('modular_id', $m['modular_id'])
                    ->where('theme_id', $t['theme_id'])
                    ->get()
                    ->groupBy('filed_name');
            }
        }

        return $re;
    }


    /**
     * 检查商品信息
     * @param array $goodsData 商品信息数组
     * @return bool
     */
    public static function checkGoodsData($goodsData)
    {
        if ($goodsData['status'] == Goods::STATUS['下架'])
            throw new Exception('含有已下架商品');

        if (!($goodsData && $goodsData['one_goods_price']))
            throw new Exception('未发现商品信息');

        if ($goodsData['one_goods_price']['price'] <= 0)
            throw new Exception('含有不出售商品');

        return true;
    }

    /**
     * 组装数组
     * @param $request
     * @return array
     */
    public static function assembleArr($request)
    {
        $arr                 = [];
        $arr['title']        = htmlspecialchars($request->title);
        $arr['html_title']   = htmlspecialchars($request->title);
        $arr['title_about']  = htmlspecialchars($request->title_about);
        $arr['qq_ID']        = htmlspecialchars($request->qq_ID);
        $arr['modular_id']   = htmlspecialchars($request->modular_id);
        $arr['modular_name'] = Modular::whereModularId($request->modular_id)->value('modular_name');
        $arr['theme_id']     = htmlspecialchars($request->theme_id);
        $arr['theme_name']   = Theme::whereThemeId($request->theme_id)->value('theme_name');
        $arr['filed_id']     = htmlspecialchars($request->filed_id);
        $arr['filed_name']   = Filed::whereFiledId($request->filed_id)->value('filed_name');
        $arr['remarks']      = htmlspecialchars($request->remarks);
        $arr['avatar_url']   = $request->avatar_url ? htmlspecialchars($request->avatar_url) : JWTAuth::user()->head_portrait;
        $arr['uid']          = JWTAuth::user()->uid;

        if ($request->has('weixin_ID'))
            $arr['weixin_ID'] = htmlspecialchars($request->weixin_ID);

        if ($request->has('fans_num'))
            $arr['fans_num'] = htmlspecialchars($request->fans_num);

        if ($request->has('link'))
            $arr['link'] = htmlspecialchars($request->link);

        if ($request->has('case_link'))
            $arr['case_link'] = htmlspecialchars($request->case_link);

        if ($request->has('room_ID'))
            $arr['room_ID'] = htmlspecialchars($request->room_ID);

        if ($request->has('max_title_long'))
            $arr['max_title_long'] = htmlspecialchars($request->max_title_long);

        if ($request->has('auth_type'))
            $arr['auth_type'] = htmlspecialchars($request->auth_type);

        if ($request->has('news_source_status'))
            $arr['news_source_status'] = htmlspecialchars($request->news_source_status);

        if ($request->has('included_sataus'))
            $arr['included_sataus'] = htmlspecialchars($request->included_sataus);

        if ($request->has('link_type'))
            $arr['link_type'] = htmlspecialchars($request->link_type);

        if ($request->has('weekend_status'))
            $arr['weekend_status'] = htmlspecialchars($request->weekend_status);

        if ($request->has('entry_status'))
            $arr['entry_status'] = htmlspecialchars($request->entry_status);

        if ($request->has('phone_weightlevel_id')) {
            $arr['phone_weightlevel_id']  = htmlspecialchars($request->phone_weightlevel_id);
            $arr['phone_weightlevel_img'] = Weightlevel::whereWeightlevelId($request->phone_weightlevel_id)->value('img_path');
        }

        if ($request->has('pc_weightlevel_id')) {
            $arr['pc_weightlevel_id']  = htmlspecialchars($request->pc_weightlevel_id);
            $arr['pc_weightlevel_img'] = Weightlevel::whereWeightlevelId($request->pc_weightlevel_id)->value('img_path');
        }

        if ($request->has('reserve_status'))
            $arr['reserve_status'] = htmlspecialchars($request->reserve_status);

        if ($request->has('platform_id')) {
            $arr['platform_id']   = htmlspecialchars($request->platform_id);
            $platformData         = Platform::wherePlatformId($request->platform_id)->first();
            $arr['platform_name'] = $platformData->platform_name;
            $arr['platform_logo'] = $platformData->logo_path;
        }

        if ($request->has('industry_id')) {
            $arr['industry_id']   = htmlspecialchars($request->industry_id);
            $arr['industry_name'] = Industry::whereIndustryId($request->industry_id)->value('industry_name');
        }

        if ($request->has('region_id')) {
            $arr['region_id']   = htmlspecialchars($request->region_id);
            $arr['region_name'] = Region::whereRegionId($request->region_id)->value('region_name');
        }

        return $arr;
    }

    /**
     * 添加商品
     * @param array $arr 接收数据
     * @param string $priceJson 价格信息
     * @return string
     * @throws \Throwable
     */
    public static function add($arr, $priceArr)
    {
        $goodsId = '';
        DB::transaction(function () use ($arr, $priceArr, &$goodsId) {
            try {
                // 插入商品
                $arr['goods_num']  = createNum('GOODS');
                $time              = date('Y-m-d H:i:s');
                $arr['created_at'] = $time;
                $arr['updated_at'] = $time;
                $goodsId           = self::insertGetId($arr);

                // 不同模式卖家输入价格
                switch (Modular::whereModularId($arr['modular_id'])->value('settlement_type')) {
                    // 标准模式卖家输入的为price
                    case Modular::SETTLEMENT_TYPE['标准模式']:
                        $P = 'price';
                        break;

                    // 软文模式卖家输入的为底价
                    case Modular::SETTLEMENT_TYPE['软文模式']:
                        $P = 'floor_price';
                        break;
                }

                foreach ($priceArr as $priceclassify_id => $price) {
                    $PriceclassifyInfo = Priceclassify::wherePriceclassifyId($priceclassify_id)->first();
                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => $priceclassify_id,
                        'priceclassify_name' => $PriceclassifyInfo->priceclassify_name,
                        'tag'                => $PriceclassifyInfo->tag,
                        $P                   => $price
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('【商品】 创建失败，' . $e->getMessage());
                throw new Exception('保存失败');
            }
        });

        return $goodsId;
    }

    /**
     * 修改商品
     * @param string $goodsId 商品id
     * @param array $arr 商品信息数组
     * @param array $priceArr 商品价格信息数组
     * @throws \Throwable
     */
    public static function updateOP($goodsId, $arr, $priceArr)
    {
        DB::transaction(function () use ($goodsId, $arr, $priceArr) {
            try {
                // 修改商品信息
                $arr['verify_status'] = Goods::VERIFY_STATUS['待审核'];
                $arr['status']        = Goods::STATUS['下架'];
                Goods::whereGoodsId($goodsId)->update($arr);

                // 删除商品价格重新创建
                GoodsPrice::whereGoodsId($goodsId)->delete();

                // 不同模式卖家输入价格
                switch (Modular::whereModularId($arr['modular_id'])->value('settlement_type')) {
                    // 标准模式卖家输入的为price
                    case Modular::SETTLEMENT_TYPE['标准模式']:
                        $P = 'price';
                        break;

                    // 软文模式卖家输入的为底价
                    case Modular::SETTLEMENT_TYPE['软文模式']:
                        $P = 'floor_price';
                        break;
                }

                foreach ($priceArr as $priceclassify_id => $price) {
                    $PriceclassifyInfo = Priceclassify::wherePriceclassifyId($priceclassify_id)->first();
                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => $priceclassify_id,
                        'priceclassify_name' => $PriceclassifyInfo->priceclassify_name,
                        'tag'                => $PriceclassifyInfo->tag,
                        $P                   => $price
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('【商品】 修改失败，' . $e->getMessage());
                throw new Exception('保存失败');
            }
        });
    }

    // 获取用户商品
    public static function getUserGoods()
    {
        return self::with('goods_price')
            ->where('uid', JWTAuth::user()->uid)
            ->where('delete_status', self::DELETE_STATUS['未删除'])
            ->get();
    }

    /**
     * 获取商品
     * @param mixed $request 筛选条件
     * @param array $whereInGoodsIdArr 限制商品范围id数组
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getGoods($request, $whereInGoodsIdArr)
    {
        $query = self::with('goods_price')
            ->where('status', self::STATUS['上架'])
            ->where('verify_status', self::VERIFY_STATUS['已通过'])
            ->where('delete_status', self::DELETE_STATUS['未删除']);

        if (!empty($whereInGoodsIdArr))
            $query->whereIn('goods_id', $whereInGoodsIdArr);

        if ($request->modular_id !== null)
            $query->where('modular_id', $request->modular_id);

        if ($request->theme_id !== null)
            $query->where('theme_id', $request->theme_id);

        if ($request->key_word !== null)
            $query->whereRaw('(title like ? or title_about like ?)', ["%{$request->key_word}%", "%{$request->key_word}%"]);

        if ($request->filed_id !== null)
            $query->where('filed_id', $request->filed_id);

        if ($request->platform_id !== null)
            $query->where('platform_id', $request->platform_id);

        if ($request->industry_id !== null)
            $query->where('industry_id', $request->industry_id);

        if ($request->region_id !== null)
            $query->where('region_id', $request->region_id);

        if ($request->fansnumlevel_min !== null)
            $query->where('fans_num', '>', $request->fansnumlevel_min);

        if ($request->fansnumlevel_max !== null)
            $query->where('fans_num', '<=', $request->fansnumlevel_max);

        if ($request->readlevel_min !== null)
            $query->where('avg_read_num', '>', $request->readlevel_min);

        if ($request->readlevel_max !== null)
            $query->where('avg_read_num', '<=', $request->readlevel_max);

        if ($request->likelevel_min !== null)
            $query->where('avg_like_num', '>', $request->likelevel_min);

        if ($request->likelevel_max !== null)
            $query->where('avg_like_num', '<=', $request->likelevel_max);

        if ($request->auth_type !== null)
            $query->where('auth_type', $request->auth_type);

        if ($request->weekend_status !== null)
            $query->where('weekend_status', $request->weekend_status);

        if ($request->included_sataus !== null)
            $query->where('included_sataus', $request->included_sataus);

        return $query->paginate();
    }

    /**
     * 下架
     * @param mixed $goods 商品信息对象
     */
    public static function down($goods)
    {
        $goods->status = self::STATUS['下架'];
        $re            = $goods->save();
        if (!$re)
            throw new Exception('操作失败');
    }

    /**
     * 添加微信基础数据
     * @param string $goodsId 商品id
     * @param string $title 公众号名称
     */
    public static function addWeiXinBasicData($goodsId, $title)
    {
        // 查询自库数据
        $re = DB::connection('weixin_mongodb')
            ->collection('WeiXin_OfficialAccount_Analysis')
            ->where('OfficialAccount_Name', $title)
            ->first();

        // 存入商品表中
        if ($re)
            self::whereGoodsId($goodsId)->update([
                'avg_read_num'    => $re['Avg_Read_Num'],
                'avg_like_num'    => $re['Avg_Like_Num'],
                'avg_comment_num' => $re['Avg_Comment_Num'],
                'avatar_url'      => $re['BasicInfo']['Avatar_Url'],
                'qrcode_url'      => $re['BasicInfo']['Qrcode_Url']]);

    }

    /**
     * 添加微博基础数据
     * @param string $goodsId 商品id
     * @param string $link 微博链接
     */
    public static function addWeiBoBasicData($goodsId, $link)
    {
        // 截取链接最后数组ID
        if (strpos($link, '?')) {
            $arr = explode('/', substr($link, 0, strpos($link, '?')));
        } else {
            $arr = explode('/', $link);
        }
        $id = end($arr);

        // 查询自库数据
        $re = DB::connection('weibo_mongodb')
            ->collection('WeiBo_Analysis')
            ->where('WeiBo_Uid', $id)
            ->first();

        // 存入商品表中
        if ($re)
            self::whereGoodsId($goodsId)->update([
                'avg_like_num'    => $re['Avg_Like_Num_Last10'],
                'avg_comment_num' => $re['Avg_Comment_Num_Last10'],
                'avg_retweet_num' => $re['Avg_Retweet_Num_Last10'],
                'avatar_url'      => $re['BasicInfo']['Avatar_Url'],
                'fans_num'        => $re['BasicInfo']['Fans_Num']
            ]);

    }

    /**
     * 添加小红书基础数据
     * @param string $goodsId 商品id
     * @param string $room_ID 小红书id
     */
    public static function addXiaoHongShuBasicData($goodsId, $room_ID)
    {
        // 查询自库数据
        $re = DB::connection('xiaohongshu_mongodb')
            ->collection('XiaoHongShu_Analysis')
            ->where('BasicInfo.XiaoHongShu_Id', $room_ID)
            ->first();

        // 存入商品表中
        if ($re)
            self::whereGoodsId($goodsId)->update([
                'fans_num'        => $re['Estimated_Fans_Num'],
                'avg_read_num'    => $re['Avg_Read_Num_Top'],
                'max_read_num'    => $re['Max_Read_Num_Top'],
                'avg_like_num'    => $re['Avg_Like_Num_Top'],
                'max_like_num'    => $re['Max_Like_Num_Top'],
                'avg_comment_num' => $re['Avg_Comment_Num_Top'],
                'max_comment_num' => $re['Max_Comment_Num_Top']
            ]);
    }

    /**
     * 删除微信初始商品
     *  1.包括 微信公众号 / 微博 / 短视频小红书
     *  2.初始创造的一批商品,当用户录入商品后，判断初始商品中是否有重复的，有则删除初始商品
     * @param string $goodsId 商品id
     * @param int $type 类型
     * @return bool
     * @throws \Exception
     */
    public static function delSelfCreateGoods($goodsId, $type)
    {
        // 商品数据
        $goods = DB::table('data_goods')->where('goods_id', $goodsId)->first();
        if (!$goods) return false;
        $delArr = [];

        switch ($type) {
            // 微信公众号
            case 1:
                if ($goods->weixin_ID) {
                    $delArr = DB::table('data_goods')
                        ->where('uid', User::GF_SELLER)
                        ->where('modular_name', '微信营销')
                        ->where('theme_name', '公众号')
                        ->where('title', $goods->title)
                        ->pluck('goods_id');
                }
                break;

            // 微博
            case 2:
                if ($goods->link) {
                    $delArr = DB::table('data_goods')
                        ->where('uid', User::GF_SELLER)
                        ->where('modular_name', '微博营销')
                        ->where('link', $goods->link)
                        ->pluck('goods_id');
                }
                break;

            // 小红书
            case 3:
                if ($goods->room_ID) {
                    $delArr = DB::table('data_goods')
                        ->where('uid', User::GF_SELLER)
                        ->where('modular_name', '视频营销')
                        ->where('theme_name', '短视频')
                        ->where('platform_name', '小红书')
                        ->where('room_ID', $goods->room_ID)
                        ->pluck('goods_id');
                }
                break;
        }

        // 删除初始商品
        foreach ($delArr as $goods_id) {
            Goods::whereGoodsId($goods_id)->delete();
            GoodsPrice::whereGoodsId($goods_id)->delete();
        }

        return true;
    }

    // 禁止重复商品
    public static function banRepeatGoods($goods_title)
    {
         $query = self::whereTitle($goods_title)
             ->where('delete_status', self::DELETE_STATUS['未删除']);

         // 屏蔽假数据
         $query->whereNotIn('uid', [User::GF_SELLER]);

         if($query->count())
             throw new Exception('已存在此商品名称');
    }
}