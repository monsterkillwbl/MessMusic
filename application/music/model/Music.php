<?php
namespace app\music\model;
use think\Model;

class Music extends Model{
	public function curl_get($url){
	    $refer = "http://music.163.com/";
	    $header = array(
	    	'Cookie: ' . 'appver=1.5.0.75771;',
			'CLIENT-IP: '.getIp(),
			'X-FORWARDED-FOR: '.getIp()
		);
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	    curl_setopt($ch, CURLOPT_USERAGENT,  "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_REFERER, $refer);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
	// 歌词获取模块
	public function getNECSongLyric($id){
        $url = 'https://api.hibai.cn/music/Music/Music?id='.$id.'&type=lrc';
        return $url;
	}
	// 获取音乐封面图
    public function getNECSongPics($id){
    	$url = 'https://api.hibai.cn/music/Music/Music?id='.$id.'&type=pic';
        return $url;
    }
    // 获取音乐资源链接
    public function getNECSongResURL($id){
        $url = 'https://api.hibai.cn/music/Music/Music?id='.$id.'&type=url';
        return $url;
    }
}
