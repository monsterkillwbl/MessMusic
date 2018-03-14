<?php
namespace app\music\service;
use think\Model;

class Discover extends Model {
	//搜索所有平台音乐
    public function getMusicDiscover(){
    	$music =  model('music/Music','model');
    	$url = 'http://music.163.com/discover/playlist/';
        $discover = $music->curl_get($url);
        //去除多余内容
        $discover=preg_replace("/[\t\n\r]+/","",$discover);
        //匹配内容
        $pattern = '/<li><div class="u-cover u-cover-1"><img class="j-flag" src="(.*?)"\/><a title="(.*?)" href="(.*?)" class="msk"><\/a><div class="bottom"><a class="icon-play f-fr" title="播放" href="javascript:;"data-res-type="13"data-res-id="(.*?)"data-res-action="play"><\/a><span class="icon-headset"><\/span><span class="nb">(.*?)<\/span><\/div><\/div><p class="dec"><a title="(.*?)" href="(.*?)" class="tit f-thide s-fc0">(.*?)<\/a><\/p><p><span class="s-fc4">by<\/span> <a title="(.*?)" href="(.*?)" class="nm nm-icn f-thide s-fc3">(.*?)<\/a>(.*?)<\/p><\/li>/i';
        //获取匹配内容
        preg_match_all($pattern, $discover, $list);
        //组合内容
        foreach ($list[1] as $key => $value) {
            $body[$key]['discover_id'] = $list[4][$key];
            $body[$key]['discover_title'] = $list[2][$key];
            $body[$key]['discover_pic'] = $list[1][$key];
        }
    	return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
    
    //搜索所有平台音乐
    public function getKgDiscover(){
        $music =  model('music/KgMusic','model');
        $url = 'http://www.kugou.com/yy/html/special.html';
        $discover = $music->curl_get($url);
        //去除多余内容
        $discover=preg_replace("/[\t\n\r]+/","",$discover);
        //匹配内容
        $pattern = '/<a href="(.*?)" class="pc_temp_btn_s02 pc_temp_bicon_share" title="分享" data-id="(.*?)" data-creat="(.*?)" data-collection="(.*?)" data-img="(.*?)"  hidefocus="true">/i';
        //获取匹配内容
        preg_match_all($pattern, $discover, $list);
        //组合内容
        foreach ($list[1] as $key => $value) {
            $body[$key]['discover_id'] = $list[2][$key];
            $body[$key]['discover_title'] = $list[4][$key];
            $body[$key]['discover_pic'] = $list[5][$key];
        }
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
}
