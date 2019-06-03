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
2. `createSoftarticleGoods` 添加软文商品接口