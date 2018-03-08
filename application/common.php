<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function get_rand_ip(){
		$arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
		$randarr= mt_rand(0,count($arr_1));
		$ip1id = $arr_1[$randarr];
		$ip2id=  round(rand(600000,  2550000)  /  10000);
		$ip3id=  round(rand(600000,  2550000)  /  10000);
		$ip4id=  round(rand(600000,  2550000)  /  10000);
		return  $ip1id . "." . $ip2id . "." . $ip3id . "." . $ip4id;
	}
function getIp(){
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		$cip = $_SERVER['HTTP_CLIENT_IP'];
	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$cip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif(!empty($_SERVER['REMOTE_ADDR'])){
		$cip = $_SERVER['REMOTE_ADDR'];
	}else{
		$cip = $this->get_rand_ip();
	}
	return $cip;
}