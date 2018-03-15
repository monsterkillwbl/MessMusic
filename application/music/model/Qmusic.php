<?php
namespace app\music\model;
use think\Model;

class Qmusic extends Model{
	public function curl_get($url){
	    $refer = "http://y.qq.com/";
	    $header = array(
			'CLIENT-IP: '.getIp(),
			'X-FORWARDED-FOR: '.getIp()
		);
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	    curl_setopt($ch, CURLOPT_REFERER, $refer);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
    // 获取QQ音乐资源链接
	public function getQSongResURL($id){
        $url = 'https://api.hibai.cn/music/Qmusic/Music?id='.$id.'&type=url';
        return $url;
	}
	// 获取QQ音乐图片
	public function getQSongPic($id){
        $url = 'https://api.hibai.cn/music/Qmusic/Music?id='.$id.'&type=pic';
        return $url;
	}
	// 获取QQ音乐歌词
	public function getQSongLrc($id){
        $url = 'https://api.hibai.cn/music/Qmusic/Music?id='.$id.'&type=lrc';
        return $url;
	}
}
