API
===
目前已有API接口：网易云音乐、酷狗音乐、QQ音乐、ONE(一个)每日一篇文字
>最新增加数据库缓存请求次数、内容、结果(务必安装数据库后使用)
### 1. 请求地址
1. [音乐网站](https://www.7cwa.com)
2. 入口：[https://api.hibai.cn/api/index/index](https://api.hibai.cn/api/index/index)
3. 测试：[demo](https://api.hibai.cn/api/demo/index)

### 2. 请求示例
>上送报文说明：
1. TransCode 必须填写
2. OpenId 必须填写
3. Body 上传报文(根据请求码填写内容)
#### 2.1 歌单请求示例
##### (网易云示例)
>请求方式：POST

>请求地址：https://api.hibai.cn/api/index/index

>上送报文：
```
{
    "TransCode": "020112",
    "OpenId": "123456789",
    "Body": {
        "SongListId": "141998290"
    }
}
```

>下传报文：
```
{
    "ResultCode": 1,
    "ErrCode": "OK",
    "Body": [
        {
            "title": "野子",
            "author": "苏运莹",
            "pic": "https://api.hibai.cn/music/index/music?id=401723037&type=pic",
            "url": "https://api.hibai.cn/music/index/music?id=401723037&type=url",
            "lrc": "https://api.hibai.cn/music/index/music?id=401723037&type=lrc"
        },
        {
            "title": "你根本不懂",
            "author": "季彦霖",
            "pic": "https://api.hibai.cn/music/index/music?id=517009807&type=pic",
            "url": "https://api.hibai.cn/music/index/music?id=517009807&type=url",
            "lrc": "https://api.hibai.cn/music/index/music?id=517009807&type=lrc"
        }
        ......
    ]
}
```
#### 2.1 音乐请求示例
##### (酷狗音乐示例)
>请求方式：POST

>请求地址：https://api.hibai.cn/api/index/index

>上送报文：
```
{
    "TransCode": "020224",
    "OpenId": "123456789",
    "Body": {
        "SongId": "9283C8FEA2871E449328BCD49D283471"
    }
}
````

>下传报文：
```
{
    "ResultCode": 1,
    "ErrCode": "OK",
    "Body": "https://api.hibai.cn/music/index/kgMusic?id=9283C8FEA2871E449328BCD49D283471&type=lrc"
}
```

### 3. 请求代码

1. 020111 获取网易云歌单信息(SongListId必填)
1. 020112 获取网易云歌单信息(Aplayer播放器专用)(SongListId必填)
1. 020113 获取网易云音乐歌词(SongId必填)
1. 020114 获取网易云音乐图片(SongId必填)
1. 020115 获取网易云音乐连接(SongId必填)
1. 020116 搜索网易云音乐(key必填)
1. 020117 网易云音乐热歌榜(key必填)

1. 020221 获取酷狗音乐歌单信息(SongListId必填)
1. 020222 获取酷狗音乐歌词(SongId必填)
1. 020223 获取酷狗音乐图片(SongId必填)
1. 020224 获取酷狗音乐链接(SongId必填)
1. 020225 搜索酷狗音乐音乐(key必填)
1. 020226 酷狗音乐热歌榜前50

1. 020331 获取QQ音乐歌单信息(SongListId必填)
1. 020332 获取QQ音乐歌词(SongId必填)
1. 020333 获取QQ音乐图片(SongId必填)
1. 020334 获取QQ音乐链接(SongId必填)
1. 020335 获取QQ音乐歌单(Aplayer播放器专用)(SongListId必填)
1. 020336 搜索QQ音乐音乐(key必填)
1. 020337 搜索QQ音乐排行榜

1. 020441 搜索QQ酷狗网易云音乐(key必填)

1. 020551 获取网易云精选歌单
1. 020552 获取酷狗音乐精选歌单
1. 020553 获取QQ音乐精选歌单

1. 030111 获取ONE(一个)当天的一篇文字
1. 030112 获取ONE(一个)最近一周的一篇文字


### 4. 错误码

1. ErrCode:0000 => OpenID不存在
1. ErrCode:9999 => type或id不存在
1. ErrCode:0001 => 交易码不存在
1. ErrCode:0002 => 非法请求或非POST请求
1. ErrCode:0003 => 模块不存在
1. ErrCode:0004 => 控制器不存在
1. ErrCode:0005 => 方法不存在

1. ErrCode:1001 => 网易云歌单ID不存在
1. ErrCode:1002 => 网易云音乐ID不存在
1. ErrCode:1003 => 网易云音乐歌单不存在
1. ErrCode:1004 => 网易云音乐搜索关键词不存在

1. ErrCode:2001 => 酷狗音乐歌单ID不存在
1. ErrCode:2002 => 酷狗音乐音乐ID不存在
1. ErrCode:2003 => 酷狗音乐歌单不存在
1. ErrCode:2004 => 酷狗音乐搜索关键词不存在

1. ErrCode:3001 => QQ音乐歌单ID不存在
1. ErrCode:3002 => QQ音乐音乐ID不存在
1. ErrCode:3003 => QQ音乐歌单不存在
1. ErrCode:3004 => QQ音乐搜索关键词不存在

1. ErrCode:4001=> 搜索关键词不存在
