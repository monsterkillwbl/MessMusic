<?php
namespace app\other\service;
use think\Model;

class Other extends Model {
	public function getOneApi(){
		//请求url
		$url = 'http://v3.wufazhuce.com:8000/api/onelist/idlist';
		//实例化model
		$other = model('other/Other','model');
		//获取请求内容
		$content = $other->curl_get($url);
		//格式化JSON数据
		$content = json_decode($content,1);
		//查询当前数据是否已经存在
		$where['id'] = $content['data'][0];
		$res = $other->where($where)->field('id,vol,img_url,img_author,img_kind,date,url,word,word_from,word_id')->find();
		//如果存在
		if($res){
			$body = $res;
		}else{
			// 请求数据
			$vContent = $other->curl_get('http://v3.wufazhuce.com:8000/api/onelist/'.$content['data'][0].'/0');
			//格式化JSON数据
			$vContent = json_decode($vContent,1);
			$data = $vContent['data']['content_list'];
			//取出数据
			$body['id'] = $vContent['data']['id'];
			$body['vol'] = $data[0]['volume'];
			$body['img_url'] = $data[0]['img_url'];
			$body['img_author'] = $data[0]['pic_info'];
			$body['img_kind'] = $data[0]['title'];
			$body['date'] = $data[0]['post_date'];
			$body['url'] = $data[0]['share_url'];
			$body['word'] = $data[0]['forward'];
			$body['word_from'] = $data[0]['words_info'];
			$body['word_id'] = $data[0]['item_id'];
			//保存数据到数据库
			$res = $other->save($body);
		}
		// 返回内容
		return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
	}
	public function getOneAllApi(){
		//请求url
		$url = 'http://v3.wufazhuce.com:8000/api/onelist/idlist';
		//实例化model
		$other = model('other/Other','model');
		//获取请求内容
		$content = $other->curl_get($url);
		//格式化JSON数据
		$content = json_decode($content,1);
		foreach($content['data'] as $key => $value){
			$where['id'] = $value;
			$res = $other->where($where)->field('id,vol,img_url,img_author,img_kind,date,url,word,word_from,word_id')->find();
			if($res){
				$body[$key] = $res;
			}else{
				$vContent = $other->curl_get('http://v3.wufazhuce.com:8000/api/onelist/'.$value.'/0');
				//格式化JSON数据
				$vContent = json_decode($vContent,1);
				$content_list = $vContent['data']['content_list'];
				//取出数据
				$body[$key]['id'] = $vContent['data']['id'];
				$body[$key]['vol'] = $content_list[0]['volume'];
				$body[$key]['img_url'] = $content_list[0]['img_url'];
				$body[$key]['img_author'] = $content_list[0]['pic_info'];
				$body[$key]['img_kind'] = $content_list[0]['title'];
				$body[$key]['date'] = $content_list[0]['post_date'];
				$body[$key]['url'] = $content_list[0]['share_url'];
				$body[$key]['word'] = $content_list[0]['forward'];
				$body[$key]['word_from'] = $content_list[0]['words_info'];
				$body[$key]['word_id'] = $content_list[0]['item_id'];
				//保存数据到数据库
				$other->create($body[$key]);
			}
		}
		// 返回内容
		return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
	}
}