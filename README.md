## 2019/5/14
1. 分析鱼爪商品模式
2. 构建 `媒体平台`, ` 平台分类`, `平台领域` 表结构
3. 建立 `微信商品`, `微博商品`，`短视频商品` ， `自媒体商品` 表
4. 讨论**前后分离**模式页面渲染的SEO问题

## 2019/5/15
1. 构建 `软文商品` 表结构  
2. 阿里云oss接入  
3. 后台框架适配安装  
4. 后台 `媒体平台` 功能

## 2019/5/16
1. 后台 `平台领域` 功能
2. 后台平台领域数据录入
3. 数据表分模块重构
4. 微信模块表:  
`微信粉丝量级`   
`微信领域`   
`微信价格种类`  
`微信平均阅读量级`  
`微信主题`  
`微信主题领域关联`  
5. 后台功能:  
`微信主题`  
`微信领域`  

## 2019/5/17
1. 微信模块表:  
`微信平均点赞量级`  
`微信主题粉丝量级关联`  
`微信主题价格种类关联`  
`微信主题平均阅读量级关联`  
`微信主题平均点赞量级关联`  
2. 微博模块表:  
`微博主题`  
`微博领域`  
`微博价格种类`  
`微博粉丝量级`  
`微博认证类型`  
`微博主题领域关联`  
3. 后台功能:  
`微信粉丝量级`   
`微信价格种类`  
`微信平均阅读量级`  
`微信平均点赞量级`  
`微博主题`  
`微博领域`  
4. `操作日志` 构建
5. 修改框架，封装 `删除依赖数据` 方法  

##2019/5/18
1. 微博模块表:  
`微博主题认证类型`  
`微博主题粉丝量级`  
`微博主题价格种类`    
`视频主题` 
`视频领域`   
`视频价格种类`  
`视频平台`  
`视频粉丝量级`  
`视频主题领域关联`  
`视频主题平台关联`  
2. 后台功能:  
`微博粉丝量级`  
`微博价格种类`  
`微博认证类型`  
`视频主题`  
`视频领域`  
`视频主题`  

## 2019/5/30
1. `savePhone` 修改手机号接口  
2. `logSaveuserinfo` 用户信息修改日志  
3. `savePass` 修改密码接口  
4. `videoGoodsAttribute` 视频商品属性  
5. `selfmediaGoodsAttribute` 自媒体商品属性  
6. `softarticleGoodsAttribute` 软文商品属性  
7. `个人实名认证` ,`企业实名认证` 接口校对  
8. 添加 `上传日志`  

## 2019/5/31
1. `createWeixinGoods` 添加微信商品接口
2. `currencyGoodsAttribute` 公共商品属性 
3. 联调测试 添加微信商品 及 商品价格种类  
4. 讨论 `交易流程`，`赔偿保证金`， `基础数据接口`

## 2019/6/1
1. 安装，项目连接 `maogoDB`  
2. 构建队列执行查询，从 `maogoDB` 中获取微信公众号基础信息  
3. `createWeiboGoods` 添加微博商品接口  
4. `createVideoGoods` 添加视频商品接口  
5. `goods_selfmedia` 自媒体商品表  

## 2019/6/3
1. `createSelfmediaGoods` 添加自媒体商品接口
2. `goods_softarticle` 软文商品表  
3. `createSoftarticleGoods` 添加软文商品接口  
4. 线上安装 `php_mongodb` 扩展  
5. 调证扩展依赖适应本地与线上php版本不同（7.2.0 | 7.0.0）

## 2019/6/4
1. `Xdebug` 调试
2. `selectWeixinGoods` 搜索微信商品 

## 2019/6/5
1. 创建商品表单验证增加表内存在 `exists` 验证
2. `selectWeiboGoods` 搜索微信商品
3. `selectVideoGoods` 搜索视频商品
4. `selectSelfmediaGoods` 搜索自媒体商品
5. `selectSoftarticleGoods` 搜索软文商品
6. 后台 `软文套餐` 功能

## 2019/6/6
1. `userGoods` 用户创建的商品
2. 模块设定
3. 商品状态设定
4. `collectionGoods` 收藏商品
5. `delCollection` 删除收藏
6. `goodsInfo` 商品信息-兼容所有模块

## 2019/6/10
1. `getCollectio` 获取收藏信息
2. 整合模块信息搜索 通过 `modular_type` 进行区分检索，  
统一方法获取模块信息
3. `user_wallet` 用户钱包表设计
4. 修改 `自媒体` `软文` 价格设计，添加
 `selfmedia_priceclassify` ,
 `selfmedia_theme_priceclassify` , 
 `softarticle_priceclassify` ,
 `softarticle_theme_priceclassify`
 表与  
 后台 `价格种类` 功能
 
 ## 2019/6/11
 1. 加入购物车 `joinShopcart`
 2. 从购物车删除 `ShopcartDel`
 3. 购物车数据 `getShopcart`
 4. 修改价格种类 `shopcartChangePriceclassify`
 5. 所有上传添加删除状态 `delete_status`
 6. 创建订单表 `indent_info`
 
 ## 2019/6/12 + 2019/6/13 + 2019/6/14
 1. 重新设计商品属性
 2. 合并模块属性
 3. 建立新结构表，添加关联
 4. 添加后台操作功能
 5. 统一商品所有接口
  
 ## 2019/6/15
 1. 五个模块 创建商品统一接口
 2. 字段验证，api的队列请求
 3. 后台创建软文套餐
 
 ## 2019/6/17
 1. `goodsBelongToUser` 个人所有商品
 2. `selectGoods` 搜索商品
 3. `collectionGoods` 收藏商品
 4. `getCollection` 获取收藏商品
 5. `delCollection` 删除收藏商品
 6. `joinShopcart` 加入购物车
 7. `getShopcart` 获取购物车商品
 8. `delShopcart`删除购物车商品
 
 ## 2019/6/18
 1. `createIndent` 创建订单
 2. 服务器配置，后端API部署

## 2019/6/19
1. 宝塔webhook + github 自动推送