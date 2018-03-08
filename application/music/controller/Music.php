<?php
namespace app\music\controller;
use think\Controller;

class Music extends Controller{
    // 网易云获取资源专用
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
    	$music = model('music/Music','model');
    	// 获取歌词
    	if($get['type']=='lrc'){
    		// 请求地址
    		$url = 'https://music.163.com/api/song/lyric?os=pc&id='.$id.'&lv=-1&kv=-1&tv=-1';
    		$lyricInfo = $music->curl_get($url);
		    // 格式化
	        $lyricInfo = json_decode($lyricInfo,1);
	        // 返回歌词内容
	        if(isset($lyricInfo['lrc']['lyric'])){
	           return $lyricInfo['lrc']['lyric'];
	        }else{
	           return '暂无歌词';
	        }
	    }
	    // 获取URL地址
    	if($get['type']=='url'){
    		$url = 'http://music.163.com/song/media/outer/url?id='.$id.'.mp3';
    		$resInfo = get_headers($url, TRUE);
        	$resInfo = str_replace('http', 'https', $resInfo);
            if($resInfo['Location']=='https://music.163.com/404'){
                $resInfo['Location'] = 'https://api.hibai.cn/empty.mp3';
            }
        	$this->redirect($resInfo['Location']);
    	}
    	// 获取图片地址
    	if($get['type']=='pic'){
    		$url = 'http://music.163.com/api/song/detail/?id='.$id.'&ids=['.$id.']';
			$picInfo = $music->curl_get($url);
	        $picInfo = str_replace('http', 'https', $picInfo);
			$picInfo = str_replace('\\', '', $picInfo);
	        $picInfo = json_decode($picInfo,1);
	        $this->redirect($picInfo['songs'][0]['album']['picUrl']);
    	}
        return json(['ResultCode'=>1,'ErrCode'=>'9999','ErrMsg'=>'type or id not exists']);
    }
}