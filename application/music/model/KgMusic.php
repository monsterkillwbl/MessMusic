<?php
namespace app\music\model;
use think\Model;

class KgMusic extends Model{
	public function curl_get($url){
	    $refer = "http://www.kugou.com/";
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
	    curl_setopt($ch, CURLOPT_REFERER, $refer);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
    // 获取酷狗音乐资源链接
	public function getKgSongResURL($id){
        $url = 'https://api.hibai.cn/music/Kgmusic/Music?id='.$id.'&type=url';
        return $url;
	}
	// 获取酷狗音乐图片
	public function getKgSongPic($id){
        $url = 'https://api.hibai.cn/music/Kgmusic/Music?id='.$id.'&type=pic';
        return $url;
	}
	// 获取酷狗音乐歌词
	public function getKgSongLrc($id){
        $url = 'https://api.hibai.cn/music/Kgmusic/Music?id='.$id.'&type=lrc';
        return $url;
	}
}
