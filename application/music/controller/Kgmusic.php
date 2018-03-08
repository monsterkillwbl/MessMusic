<?php
namespace app\music\controller;
use think\Controller;

class Kgmusic extends Controller{
	// 酷狗获取资源专用
    function Music(){
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
        $music = model('music/KgMusic','model');
        // 获取KGURL地址
        if($get['type']=='url'){
            $url = 'http://m.kugou.com/app/i/getSongInfo.php?cmd=playInfo&hash='.$id;
            $resInfo = $music->curl_get($url);
            $resInfo = str_replace('\\', '', $resInfo);
            $resInfo = json_decode($resInfo,1);
            $this->redirect($resInfo['url']);
        }
        // 获取KGPic地址
        if($get['type']=='pic'){
            $url = 'http://m.kugou.com/app/i/getSongInfo.php?cmd=playInfo&hash='.$id;
            $picInfo = $music->curl_get($url);
            $picInfo = str_replace('\\', '', $picInfo);
            $picInfo = json_decode($picInfo,1);
            $picInfo['album_img'] = str_replace('{size}/','',$picInfo['album_img']);
            $this->redirect($picInfo['album_img']);
        }
        // 获取KGPic地址
        if($get['type']=='lrc'){
            $url = 'http://www.kugou.com/yy/index.php?r=play/getdata&hash='.$id;
            $lyricInfo = $music->curl_get($url);
            $lyricInfo = json_decode($lyricInfo,1);
            return $lyricInfo['data']['lyrics'];
        }
        return json(['ResultCode'=>1,'ErrCode'=>'9999','ErrMsg'=>'type or id not exists']);
    }
}
