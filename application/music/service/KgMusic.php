<?php
namespace app\music\service;
use think\Model;

class KgMusic extends Model {

    //获取酷狗音乐歌单信息列表
    public function getKgSongList(){
        $post = input('post.');
        if(!isset($post['Body']['SongListId'])){
            return ['ResultCode'=>1,'ErrCode'=>'2001','ErrMsg'=>'KuGou SongListId not exists'];
        }
        $url = 'http://mobilecdn.kugou.com/api/v3/special/song?plat=0&specialid='.$post['Body']['SongListId'].'&page=1&pagesize=-1&version=8352&with_res_tag=1';
        $music = model('music/KgMusic','model');
        $musicInfo = $music->curl_get($url);
        // 去除开头结尾
        $musicInfo = str_replace('<!--KG_TAG_RES_START-->', '', $musicInfo);
        $musicInfo = str_replace('<!--KG_TAG_RES_END-->', '', $musicInfo);
        // 格式化json
        $musicInfo = json_decode($musicInfo,1);
        // 判断是否存在
        if (!isset($musicInfo['data']['info'])||count($musicInfo['data']['info'])<=0) {
            return ['ResultCode'=>1,'ErrCode'=>'2003','ErrMsg'=>'KuGou SongList not exists'];
        }
        // 生成歌单
        foreach ($musicInfo['data']['info'] as $key => $value) {
            // 正则匹配分离filename
            $result = preg_split('[ - ]',$value['filename']);
            // 歌名 歌手
            $body[$key]['title'] = $result[1];
            $body[$key]['author'] = $result[0];
            //歌曲URL
            $body[$key]['url'] = $music->getKgSongResURL($value['hash']);
            //歌曲PIC
            $body[$key]['pic'] = $music->getKgSongPic($value['hash']);
            //歌曲LRC
            $body[$key]['lrc'] = $music->getKgSongLrc($value['hash']);
            //歌曲时长
            $body[$key]['time'] = $value['duration'];
        }
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }

    //获取酷狗音乐歌词
    public function getKgSongLyric(){
        $post = input('post.');
        if(!isset($post['Body']['SongId'])){
            return ['ResultCode'=>1,'ErrCode'=>'2002','ErrMsg'=>'KuGou SongId not exists'];
        }
        $body =  model('music/KgMusic','model')->getKgSongLrc($post['Body']['SongId']);
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }

    //获取酷狗音乐图片
    public function getKgSongPic(){
        $post = input('post.');
        if(!isset($post['Body']['SongId'])){
            return ['ResultCode'=>1,'ErrCode'=>'2002','ErrMsg'=>'KuGou SongId not exists'];
        }
        $body =  model('music/KgMusic','model')->getKgSongPic($post['Body']['SongId']);
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
    
    //获取酷狗音乐资源链接
    public function getKgSongResURL(){
        $post = input('post.');
        if(!isset($post['Body']['SongId'])){
            return ['ResultCode'=>1,'ErrCode'=>'2002','ErrMsg'=>'KuGou SongId not exists'];
        }
        $body =  model('music/KgMusic','model')->getKgSongResURL($post['Body']['SongId']);
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
    //搜索酷狗音乐
    public function getKgSongSearch(){
        $post = input('post.');
        if(!isset($post['Body']['key'])){
            return ['ResultCode'=>1,'ErrCode'=>'2004','ErrMsg'=>'Search Key not exists'];
        }
        $music =  model('music/KgMusic','model');
        $url = 'http://mobilecdn.kugou.com/api/v3/search/song?format=json&keyword='.$post['Body']['key'].'&page=1&pagesize=10&showtype=1';
        $musicInfo = $music->curl_get($url);
        $musicInfo = json_decode($musicInfo,1);
        //判断是否存在 否则返回null
        if(!isset($musicInfo['data']['info'])||sizeof($musicInfo['data']['info'])<=0){
            return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>'null'];
        }
        foreach ($musicInfo['data']['info'] as $key => $value) {
            $mid = $value['hash'];
            // 歌名 歌手
            $body[$key]['title'] = $value['songname'];
            $body[$key]['author'] = $value['singername'];
            $body[$key]['time'] = $value['duration'];
            //歌曲URL
            $body[$key]['url'] = $music->getKgSongResURL($value['hash']);
            //歌曲PIC
            $body[$key]['pic'] = $music->getKgSongPic($value['hash']);
            //歌曲LRC
            $body[$key]['lrc'] = $music->getKgSongLrc($value['hash']);
        }
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
    //酷狗音乐热门排行榜
    public function getKgHotSong(){
        $post = input('post.');
        $music =  model('music/KgMusic','model');
        for ($i=0; $i < 3; $i++) { 
            $url = 'http://m.kugou.com/rank/info/?rankid=8888&page='.($i+1).'&json=true';
            $url = 'http://m.kugou.com/rank/info/?rankid=8888&page='.($i+1).'&json=true';
            $musicInfo = $music->curl_get($url);
            $musicInfo = json_decode($musicInfo,1);
            //判断是否存在 否则返回null
            if(!isset($musicInfo['songs']['list'])||sizeof($musicInfo['songs']['list'])<=0){
                return ['ResultCode'=>1,'ErrCode'=>'2003','ErrMsg'=>'KuGou SongList not exists'];
            }
            foreach ($musicInfo['songs']['list'] as $key => $value) {
                $mid = $value['hash'];
                // 正则匹配分离filename
                $result = preg_split('[ - ]',$value['filename']);
                // 歌名 歌手
                $body[$key+$i*10]['title'] = $result[1];
                $body[$key+$i*10]['author'] = $result[0];
                $body[$key+$i*10]['time'] = $value['duration'];
                //歌曲URL
                $body[$key+$i*10]['url'] = $music->getKgSongResURL($value['hash']);
                //歌曲PIC
                $body[$key+$i*10]['pic'] = $music->getKgSongPic($value['hash']);
                //歌曲LRC
                $body[$key+$i*10]['lrc'] = $music->getKgSongLrc($value['hash']);
            }
        }
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
}