<?php
namespace app\music\service;
use think\Model;

class Search extends Model {
	//搜索所有平台音乐
    public function getAllSongSearch(){
    	$post = input('post.');
        if(!isset($post['Body']['key'])||$post['Body']['key']==''){
            return ['ResultCode'=>1,'ErrCode'=>'4001','ErrMsg'=>'Search Key not exists'];
        }
    	$music =  model('music/Search','model');
    	$Qmusic = $music->getQSongSearch($post['Body']['key']);
    	$NECmusic = $music->getNECSongSearch($post['Body']['key']);
    	$Kgmusic = $music->getKgSongSearch($post['Body']['key']);
    	//判断是否存在
    	$body = [];
    	if(isset($Qmusic)||$Qmusic!=null){
    		$body = array_merge($body,$Qmusic);
    	}
    	if(isset($NECmusic)||$NECmusic!=null){
    		$body = array_merge($body,$NECmusic);
    	}
    	if(isset($Kgmusic)||$Kgmusic!=null){
    		$body = array_merge($body,$Kgmusic);
    	}
    	if($body==null){
    		$body = '暂无数据';
    	}
    	return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
}
