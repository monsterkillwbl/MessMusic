<?php
namespace app\other\model;
use think\Model;

class Other extends Model{

	// 设置当前模型对应的完整数据表名称
    protected $table = 'api_one';
	public function curl_get($url){
	    $refer = "http://wufazhuce.com/";
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
}
