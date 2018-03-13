<?php
namespace app\music\service;
use think\Model;

class Music extends Model {

    // 获取歌单信息列表
    public function getNECSongList(){
        $post = input('post.');
        if(!isset($post['Body']['SongListId'])){
            return ['ResultCode'=>1,'ErrCode'=>'1001','ErrMsg'=>'SongListId not exists'];
        }
        //实例化模型
        $music = model('music/Music','model');
        $url = "https://music.163.com/api/playlist/detail?id=".$post['Body']['SongListId'];
        $musicInfo = $music->curl_get($url);
        // 格式化json
        $musicInfo = json_decode($musicInfo,1);
        // 判断是否存在
        if (!isset($musicInfo['result']['tracks'])) {
            return ['ResultCode'=>1,'ErrCode'=>'1003','ErrMsg'=>'SongList not exists'];
        }

        //歌单作者信息
        $creator['userId'] =  $musicInfo['result']['creator']['userId'];
        $creator['nickname'] =  $musicInfo['result']['creator']['nickname'];
        $creator['signature'] =  $musicInfo['result']['creator']['signature'];
        $creator['avatarUrl'] =  $musicInfo['result']['creator']['avatarUrl'];
        $creator['backgroundUrl'] =  $musicInfo['result']['creator']['backgroundUrl'];
        //歌单信息
        $body['id']  =  $musicInfo['result']['id'];
        $body['name']  =  $musicInfo['result']['name'];
        $body['coverImgUrl']  =  $musicInfo['result']['coverImgUrl'];
        $body['creator'] = $creator;

        // 循环获取歌单信息
        foreach ($musicInfo['result']['tracks'] as $key => $value) {
            //音乐ID
            $id =  $musicInfo['result']['tracks'][$key]['id'];
            $body['songs'][$key]['id']  = $id;
            //音乐名称
            $body['songs'][$key]['title'] = $musicInfo['result']['tracks'][$key]['name'];
            // 歌手名称
            $body['songs'][$key]['author'] = $musicInfo['result']['tracks'][$key]['artists'][0]['name'];
            // 歌手ID
            $body['songs'][$key]['author_id'] = $musicInfo['result']['tracks'][$key]['artists'][0]['id'];
            // 歌手图片
            $body['songs'][$key]['author_pic'] = $musicInfo['result']['tracks'][$key]['artists'][0]['picUrl'];

            // 音乐图片
            $picURL = $music->getNECSongPics($id);
            $body['songs'][$key]['pic'] = $picURL;

            // 音乐链接
            $listResURL = $music->getNECSongResURL($id);
            $body['songs'][$key]['url'] = $listResURL;

            // 音乐歌词
            $listLyric = $music->getNECSongLyric($id);
            $body['songs'][$key]['lrc'] = $listLyric;
            //时间
            $time = $value['duration'];
            $body['songs'][$key]['time'] = ceil($time/1000);
        }

        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
    // 获取歌单信息列表
    public function getHexoSongList(){
        $post = input('post.');
        if(!isset($post['Body']['SongListId'])){
            return ['ResultCode'=>1,'ErrCode'=>'1001','ErrMsg'=>'SongListId not exists'];
        }
        //实例化模型
        $music = model('music/Music','model');
        $url = "https://music.163.com/api/playlist/detail?id=".$post['Body']['SongListId'];
        $musicInfo = $music->curl_get($url);
        // 格式化json
        $musicInfo = json_decode($musicInfo,1);
        // 判断是否存在
        if (!isset($musicInfo['result']['tracks'])) {
            return ['ResultCode'=>1,'ErrCode'=>'1001','ErrMsg'=>'SongListId not exists'];
        }
        // 循环获取歌单信息
        foreach ($musicInfo['result']['tracks'] as $key => $value) {
            //音乐ID
            $id =  $musicInfo['result']['tracks'][$key]['id'];
            //音乐名称
            $body[$key]['title'] = $musicInfo['result']['tracks'][$key]['name'];
            // 歌手信息
            $body[$key]['author'] = $musicInfo['result']['tracks'][$key]['artists'][0]['name'];

            // 音乐图片
            $listPic = $music->getNECSongPics($id);
            $body[$key]['pic'] = $listPic;
            // 音乐链接
            $listResURL = $music->getNECSongResURL($id);
            $body[$key]['url'] = $listResURL;
            // 音乐歌词
            $listLyric = $music->getNECSongLyric($id);
            $body[$key]['lrc'] = $listLyric;
        }
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
    // 获取音乐歌词
    public function getNECSongLyric(){
        $post = input('post.');
        if(!isset($post['Body']['SongId'])){
            return ['ResultCode'=>1,'ErrCode'=>'1002','ErrMsg'=>'SongId not exists'];
        }
        $body = model('music/Music','model')->getNECSongLyric($post['Body']['SongId']);
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }

    // 获取音乐封面图
    public function getNECSongPics(){
        $post = input('post.');
        if(!isset($post['Body']['SongId'])){
            return ['ResultCode'=>1,'ErrCode'=>'1002','ErrMsg'=>'SongId not exists'];
        }
        $body = model('music/Music','model')->getNECSongPics($post['Body']['SongId']);
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }

    // 获取音乐资源链接
    public function getNECSongResURL(){
        $post = input('post.');
        if(!isset($post['Body']['SongId'])){
            return ['ResultCode'=>1,'ErrCode'=>'1002','ErrMsg'=>'SongId not exists'];
        }
        $body = model('music/Music','model')->getNECSongResURL($post['Body']['SongId']);
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
    public function getNECSongSearch(){
        $post = input('post.');
        if(!isset($post['Body']['key'])){
            return ['ResultCode'=>1,'ErrCode'=>'1004','ErrMsg'=>'NEC Search Key not exists'];
        }
        //请求地址
        $url = "http://music.163.com/api/search/pc";
        // 请求数据
        $post_data = array(
            's' => $post['Body']['key'],
            'offset' => '0',
            'limit' => '10',
            'type' => '1',
        );
        $referrer = "http://music.163.com/";
        //url转义
        $URL_Info = parse_url($url);
        $values = [];
        $result = '';
        $request = '';
        foreach ($post_data as $key => $value) {
            $values[] = "$key=" . urlencode($value);
        }
        $data_string = implode("&", $values);
        if (!isset($URL_Info["port"])) {
            $URL_Info["port"] = 80;
        }
        $request .= "POST " . $URL_Info["path"] . " HTTP/1.1\n";
        $request .= "Host: " . $URL_Info["host"] . "\n";
        $request .= "Referer: $referrer\n";
        $request .= "Content-type: application/x-www-form-urlencoded\n";
        $request .= "Content-length: " . strlen($data_string) . "\n";
        $request .= "Connection: close\n";
        $request .= "Cookie: " . "appver=1.5.0.75771;\n";
        $request .= "\n";
        $request .= $data_string . "\n";
        $fp = fsockopen($URL_Info["host"], $URL_Info["port"]);
        fputs($fp, $request);
        $i = 1;
        while (!feof($fp)) {
            if ($i >= 15) {
                $result .= fgets($fp);
            } else {
                fgets($fp);
                $i++;
            }
        }
        fclose($fp);
        // 格式化json
        $musicInfo = json_decode($result,1);
        //实例化模型
        $music = model('music/Music','model');
        //判断是否存在 否则返回null
        if(!isset($musicInfo['result']['songs'])||sizeof($musicInfo['result']['songs'])<=0){
            return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>'null'];
        }
        foreach ($musicInfo['result']['songs'] as $key => $value) {
            $id = $value['id'];
            // 歌名 歌手
            $body[$key]['title'] = $value['name'];
            $body[$key]['author'] = $value['artists'][0]['name'];
            $time = $value['duration'];
            $body[$key]['time'] = ceil($time/1000);
            // 音乐图片
            $body[$key]['pic'] = $music->getNECSongPics($id);
            // 音乐链接
            $body[$key]['url'] = $music->getNECSongResURL($id);
            // 音乐歌词
            $body[$key]['lrc'] = $music->getNECSongLyric($id);
        }
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }
    // 获取网易云热门歌单
    public function getHotSongList(){
        $post = input('post.');
        //实例化模型
        $music = model('music/Music','model');
        $url = "https://music.163.com/api/playlist/detail?id=3778678";
        $musicInfo = $music->curl_get($url);
        // 格式化json
        $musicInfo = json_decode($musicInfo,1);
        // 判断是否存在
        if (!isset($musicInfo['result']['tracks'])) {
            return ['ResultCode'=>1,'ErrCode'=>'1001','ErrMsg'=>'HotSongList not exists'];
        }
        // 循环获取歌单信息
        foreach ($musicInfo['result']['tracks'] as $key => $value) {
            //音乐ID
            $id =  $musicInfo['result']['tracks'][$key]['id'];
            //音乐名称
            $body[$key]['title'] = $musicInfo['result']['tracks'][$key]['name'];
            // 歌手信息
            $body[$key]['author'] = $musicInfo['result']['tracks'][$key]['artists'][0]['name'];

            // 音乐图片
            $listPic = $music->getNECSongPics($id);
            $body[$key]['pic'] = $listPic;
            // 音乐链接
            $listResURL = $music->getNECSongResURL($id);
            $body[$key]['url'] = $listResURL;
            // 音乐歌词
            $listLyric = $music->getNECSongLyric($id);
            $body[$key]['lrc'] = $listLyric;
            //时间
            $time = $value['duration'];
            $body[$key]['time'] = ceil($time/1000);
        }
        return ['ResultCode'=>1,'ErrCode'=>'OK','Body'=>$body];
    }

}