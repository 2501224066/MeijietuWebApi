<?php


namespace App\Models\Nb;


use App\Jobs\addWeiXinBasicsData;
use App\Models\Tb\Filed;
use App\Models\Tb\Industry;
use App\Models\Tb\Modular;
use App\Models\Tb\Platform;
use App\Models\Tb\Priceclassify;
use App\Models\Tb\Region;
use App\Models\Tb\Theme;
use App\Models\Tb\Weightlevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * App\Models\Nb\Goods
 *
 * @property int $goods_id
 * @property int|null $uid
 * @property string $goods_num 编码
 * @property string $title 标题
 * @property string $html_title 页面tile
 * @property string $title_about 标题简介
 * @property string|null $content 内容
 * @property int|null $max_title_long 限制标题长度
 * @property string|null $avatar_url 头像
 * @property string|null $qrcode_url 二维码
 * @property string|null $qq_ID QQ号
 * @property string|null $weixin_ID 微信号
 * @property string|null $room_ID 房间号
 * @property int|null $fans_num 粉丝数
 * @property int|null $auth_type 认证类型 0=未认证 1=认证
 * @property int|null $news_source_status 是否新闻源 0=否 1=是
 * @property int|null $entry_status 入口状态 1=没有入口 2=首页入口 3=频道入口 4=上级入口
 * @property int|null $included_sataus 收录状态 0=不包收录 1=包收录
 * @property string|null $link 链接
 * @property int|null $link_type 链接类型 0=不可带网址 1=可带网址
 * @property int|null $weekend_status 周末可发 0=否 1=是
 * @property int|null $reserve_status 是否预约 0=否 1=是
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
 * @property int $verify_status 审核状态 0=待审核 1=未通过 2=通过
 * @property int $status 上架状态 0=未上架 1=上架
 * @property int $delete_status 删除状态 0=未删除 1=删除
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $avg_read_num 平均阅读数
 * @property int|null $avg_like_num 平均点赞数
 * @property int|null $avg_comment_num 平均评论数
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Nb\GoodsPrice[] $goods_price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereAuthType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereAvgCommentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereAvgLikeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereAvgReadNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereDeleteStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereEntryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereFansNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereFiledName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereGoodsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereHtmlTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereIncludedSataus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereIndustryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereIndustryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereLinkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereMaxTitleLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereModularId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereModularName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereNewsSourceStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods wherePcWeightlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods wherePcWeightlevelImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods wherePhoneWeightlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods wherePhoneWeightlevelImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods wherePlatformLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods wherePlatformName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereQrcodeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereReserveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereRoomID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereTitleAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereVerifyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereWeekendStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Goods whereWeixinID($value)
 * @mixin \Eloquent
 */
class Goods extends Model
{
    protected $table = 'nb_goods';

    protected $primaryKey = 'goods_id';

    protected $guarded = [];

    const STATUS = ['下架' => 0, '上架' => 1];

    const VERIFY_STATUS = ['待审核' => 0, '未通过' => 1, '已通过' => 2];

    const DELETE_STATUS = ['未删除' => 0, '已删除' => 1];

    public function goods_price(): HasMany
    {
        return $this->hasMany(GoodsPrice::class, 'goods_id', 'goods_id');
    }

    public function one_goods_price(): HasOne
    {
        return $this->hasOne(GoodsPrice::class, 'goods_id', 'goods_id');
    }


    /**
     * 组装数组
     * @param $request
     * @return array
     */
    public static function assembleArr($request)
    {
        $arr                 = [];
        $time                = date('Y-m-d H:i:s');
        $arr['title']        = htmlspecialchars($request->title);
        $arr['html_title']   = htmlspecialchars($request->title);
        $arr['title_about']  = htmlspecialchars($request->title_about);
        $arr['qq_ID']        = htmlspecialchars($request->qq_ID);
        $arr['modular_id']   = htmlspecialchars($request->modular_id);
        $modualrData         = Modular::whereModularId($request->modular_id)->first();
        $arr['modular_name'] = $modualrData->modular_name;
        $arr['theme_id']     = htmlspecialchars($request->theme_id);
        $arr['theme_name']   = Theme::whereThemeId($request->theme_id)->value('theme_name');
        $arr['filed_id']     = htmlspecialchars($request->filed_id);
        $arr['filed_name']   = Filed::whereFiledId($request->filed_id)->value('filed_id');
        $arr['remarks']      = htmlspecialchars($request->remarks);
        $arr['created_at']   = $time;
        $arr['updated_at']   = $time;
        $arr['goods_num']    = createGoodsNnm($modualrData->abbreviation);
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
    public static function add($arr, $priceJson)
    {
        $goodsId = '';
        DB::transaction(function () use ($arr, $priceJson, &$goodsId) {
            // 插入商品
            $goodsId = self::insertGetId($arr);
            if (!$goodsId)
                throw new Exception('保存失败');

            // 插入商品价格
            $priceArr = json_decode($priceJson);
            foreach ($priceArr as $priceclassify_id => $price) {
                $st = GoodsPrice::create([
                    'goods_id'           => $goodsId,
                    'priceclassify_id'   => $priceclassify_id,
                    'priceclassify_name' => Priceclassify::wherePriceclassifyId($priceclassify_id)->value('priceclassify_name'),
                    'price'              => $price
                ]);
                if (!$st)
                    throw new Exception('保存失败');
            }
        });

        return $goodsId;
    }

    /**
     * 补充基础数据
     * @param int $goodsId 商品id
     * @param array $arr 接收数据
     * @return bool
     */
    public static function addBasicsData($goodsId, $arr)
    {
        switch (Modular::whereModularId($arr['modular_id'])->value('tag')) {
            // 微信基础数据
            case 'WEIXIN':
                addWeiXinBasicsData::dispatch($goodsId, $arr['weixin_ID'])->onQueue('addWeiXinBasicsData');
                break;

            // 微博基础数据
            //TODO...
        }

        return true;
    }

    // 获取用户商品
    public static function getUserGoods()
    {
        return self::with('goods_price')
            ->where('uid', JWTAuth::user()->uid)
            ->where('delete_status', self::DELETE_STATUS['未删除'])
            ->get();
    }

    // 获取商品
    public static function getGoods($request, $whereInGoodsIdArr)
    {
        $query = self::with('goods_price')
            ->where('status', self::STATUS['上架'])
            ->where('verify_status', self::VERIFY_STATUS['已通过'])
            ->where('modular_id', $request->modular_id)
            ->where('theme_id', $request->theme_id);

        if( $whereInGoodsIdArr)
            $query->whereIn('goods_id', $whereInGoodsIdArr);

        if ($request->has('key_word'))
            $query->where('title', 'like', '%' . $request->key_word . '%')
                ->orWhere('title_about', 'like', '%' . $request->key_word . '%');

        if ($request->has('filed_id'))
            $query->where('filed_id', $request->filed_id);

        if ($request->has('platform_id'))
            $query->where('platform_id', $request->platform_id);

        if ($request->has('industry_id'))
            $query->where('industry_id', $request->industry_id);

        if ($request->has('region_id'))
            $query->where('region_id', $request->region_id);

        if ($request->has('fanslevel_min'))
            $query->where('fans_num', '>=', $request->fanslevel_min);

        if ($request->has('fanslevel_max'))
            $query->where('fans_num_max', '<', $request->fanslevel_max);

        if ($request->has('readlevel_min'))
            $query->where('avg_read_num', '>=', $request->readlevel_min);

        if ($request->has('readlevel_max'))
            $query->where('avg_read_num', '<', $request->readlevel_max);

        if ($request->has('likelevel_min'))
            $query->where('avg_like_num', '>=', $request->likelevel_min);

        if ($request->has('likelevel_max'))
            $query->where('avg_like_num', '<', $request->likelevel_max);

        if ($request->has('weekend_status'))
            $query->where('weekend_status', $request->weekend_status);

        if ($request->has('included_sataus'))
            $query->where('included_sataus', $request->included_sataus);

        return $query->paginate();
    }

}