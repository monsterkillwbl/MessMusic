<?php
namespace app\music\controller;
use think\Controller;

class Qmusic extends Controller{
    public function Music(){
    	// 跨域使用
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        $get = input('get.');
        // 获取请求id
        if(!isset($get['id'])||!isset($get['type'])){
            return json(['ResultCode'=>1,'ErrCode'=>'9999','ErrMsg'=>'type or id not exists']);
        }else{
            $id = $get['id'];
        }
        // 实例化模型
        $music = model('music/Qmusic','model');
        if($get['type']=='lrc'){
            // 请求地址
            $url = 'https://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg?songmid='.$get['id'].'&tpl=yqq_song_detail&format=json&g_tk=5381&loginUin=0&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0';
            $SongInfo = $music->curl_get($url);
            $SongInfo = json_decode($SongInfo,1);
            // 获取id
            $songId = $SongInfo['data'][0]['id'];
            $url = 'http://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric.fcg?format=jsonp&g_tk=5381&loginUin=0&hostUin=0&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0&nobase64=1&musicid='.$songId;
            $lyricInfo = $music->curl_get($url);
            // 去除多余信息
            $lyricInfo = str_replace('MusicJsonCallback(', '', $lyricInfo);
            $lyricInfo = str_replace(')', '', $lyricInfo);
            // 格式化
            $lyricInfo = json_decode($lyricInfo,1);

            // 返回歌词内容
            if(isset($lyricInfo['lyric'])){
                // 转义html特有的ASCII编码
                return html_entity_decode($lyricInfo['lyric'], null, 'UTF-8');
            }else{
               return '[00:00:00]暂无歌词';
            }
	    }
        //获取QQ音乐资源
        if($get['type']=='url'){
            //生成guid
            $guid = rand(100000000,999999999);
            // 请求地址
            $url = 'http://c.y.qq.com/base/fcgi-bin/fcg_music_express_mobile3.fcg?format=json&platform=yqq&cid=205361747&songmid='.$id.'&filename=C400'.$id.'.m4a&guid='.$guid;
            $SongInfo = $music->curl_get($url);
            //格式化
            $SongInfo = json_decode($SongInfo,1);
            $filename = $SongInfo['data']['items'][0]['filename'];
            $vkey = $SongInfo['data']['items'][0]['vkey'];
            // 返回url
            $this->redirect('http://dl.stream.qqmusic.qq.com/'.$filename.'?vkey='.$vkey.'&guid='.$guid.'&fromtag=66');
        }
        // 获取QQ音乐图片
        if($get['type']=='pic'){
            // 请求地址
            $url = 'https://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg?songmid='.$id.'&tpl=yqq_song_detail&format=jsonp&callback=getOneSongInfoCallback&g_tk=5381&jsonpCallback=getOneSongInfoCallback&loginUin=0&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0';
            $SongInfo = $music->curl_get($url);
            // 去除多余信息
            $SongInfo = str_replace('getOneSongInfoCallback(', '', $SongInfo);
            $SongInfo = str_replace(')', '', $SongInfo);
            // 格式化
            $SongInfo = str_replace('\\', '', $SongInfo);
            $SongInfo = json_decode($SongInfo,1);
            // 获取id
            $picId = $SongInfo['data'][0]['album']['id'];
            $temp = (int)$picId%100;
            $pic = 'http://imgcache.qq.com/music/photo/album_300/'.$temp .'/300_albumpic_'.$picId.'_0.jpg';
            $this->redirect($pic);
        }
        return json(['ResultCode'=>1,'ErrCode'=>'9999','ErrMsg'=>'type or id not exists']);
    }
}
