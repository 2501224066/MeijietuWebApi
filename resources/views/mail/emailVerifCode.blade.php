<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
</head>

<style>
    .box{
        font-size: 16px;
        color: #999;
        padding: 20px;
        margin: 0;
    }
    .content666{
        margin: auto;
        width: 350px;
        padding:20px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius:2px;
        box-shadow: 10px 10px 1px #eee;
        padding-top: 22px;
        font-family: -apple-system,BlinkMacSystemFont,Helvetica Neue,PingFang SC,Microsoft YaHei,Source Han Sans SC,Noto Sans CJK SC,WenQuanYi Micro Hei,sans-serif;
    }
    .panel-default,
    .panel-heading,
    .panel-body,
    .panel-footer{
        border: none;
    }
    p{
        padding: 5px 0;
    }
    .panel-footer{
        background: #f5f5f5;
        padding: 10px;
        font-size: 14px;
        overflow: hidden;
    }
    h1{
        color:#0084ff;
        text-align: center;
    }
</style>

<body>
    <div class="box">
        <div class="content666">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>{{ $title }}</h1>
                </div>
                <div class="panel-body">
                    <p>尊敬的用户 您好：</p>
                    <p style="text-indent:2em;">您的{{ $codeType }}为【{{ $code }}】，有效期为5分钟！请在有效期内使用！<p>
                </div>
                <div class="panel-footer">
                    <span style="float:right;">时间：{{ $time }}</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>