<?php
namespace app\music\logic;
use think\Model;

class Music extends Model{
    //解析控制器内方法
    public function ControllerAnalyze($controller){
        if($controller=='01'){return 'Music';}//控制器
        if($controller=='02'){return 'KgMusic';}//控制器
        if($controller=='03'){return 'Qmusic';}//控制器
        if($controller=='04'){return 'Search';}//控制器
        return false;
    } 
    //解析操作方法
    public function ActionAnalyze($action){
        if($action=='11'){return 'getNECSongList';}//获取网易云歌单信息列表
        if($action=='12'){return 'getHexoSongList';}//获取网易云音乐资源链接
        if($action=='13'){return 'getNECSongLyric';}//获取网易云音乐歌词
        if($action=='14'){return 'getNECSongPics';}//获取网易云音乐封面图
        if($action=='15'){return 'getNECSongResURL';}//获取网易云音乐资源链接
        if($action=='16'){return 'getNECSongSearch';}//搜索网易云音乐
        if($action=='17'){return 'getHotSongList';}//搜索网易云音乐

        
        if($action=='21'){return 'getKgSongList';}//获取酷狗音乐歌单信息列表
        if($action=='22'){return 'getKgSongLyric';}//获酷狗易音乐歌词
        if($action=='23'){return 'getKgSongPic';}//获酷狗易音乐图片
        if($action=='24'){return 'getKgSongResURL';}//获取酷狗音乐资源链接
        if($action=='25'){return 'getKgSongSearch';}//搜索酷狗音乐
        if($action=='26'){return 'getKgHotSong';}//酷狗音乐热门排行榜

        if($action=='31'){return 'getQSongList';}//获取QQ音乐歌单信息列表
        if($action=='32'){return 'getQSongLyric';}//获取QQ音乐歌词
        if($action=='33'){return 'getQSongPic';}//获取QQ音乐图片
        if($action=='34'){return 'getQSongResURL';}//获取QQ音乐资源链接
        if($action=='35'){return 'getQPlayerSongList';}//获取QQ播放器歌单
        if($action=='36'){return 'getQSongSearch';}//搜索QQ音乐
        if($action=='37'){return 'getQHotSongList';}//获取QQ音乐热歌榜

        if($action=='41'){return 'getAllSongSearch';}//搜索三个平台音乐

        return false;
    }
    //检查请求数据
    public function CheckParam($action){
    }
}
