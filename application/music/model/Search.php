<?php
namespace app\music\model;
use think\Model;

class Search extends Model{
	//获取QQ音乐搜索结果
    public function getQSongSearch($word){
        $music =  model('music/Qmusic','model');
        $url = 'https://c.y.qq.com/soso/fcgi-bin/client_search_cp?ct=24&qqmusic_ver=1298&new_json=1&remoteplace=txt.yqq.center&searchid=49376627710948669&t=0&aggr=1&cr=1&catZhida=1&lossless=0&flag_qc=0&p=1&n=10&w='.$word.'&g_tk=5381&jsonpCallback=MusicJsonCallback4603211876683677&loginUin=0&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0';
        $musicInfo = $music->curl_get($url);
        //去除无用字符
        $musicInfo = str_replace('MusicJsonCallback4603211876683677(', '', $musicInfo);
        $musicInfo = str_replace(')', '', $musicInfo);
        //格式化
        $musicInfo = json_decode($musicInfo,1);
        //判断是否存在 否则返回null
        if(!isset($musicInfo['data']['song']['list'])||sizeof($musicInfo['data']['song']['list'])<=0){
            return null;
        }
        foreach ($musicInfo['data']['song']['list'] as $key => $value) {
            $mid = $value['mid'];
            // 歌名 歌手 时间
            $body[$key]['title'] = $value['name'];
            $body[$key]['author'] = $value['singer'][0]['name'];
            $body[$key]['time'] = $value['interval'];
            //歌曲URL
            $body[$key]['url'] = $music->getQSongResURL($mid);
            //歌曲PIC
            $body[$key]['pic'] = $music->getQSongPic($mid);
            //歌曲LRC
            $body[$key]['lrc'] = $music->getQSongLrc($mid);
            //来源
            $body[$key]['origin'] = 1;
        }
        return $body;
    }
    //获取网易云搜索结果
    public function getNECSongSearch($word){
        //请求地址
        $url = "http://music.163.com/api/search/pc";
        // 请求数据
        $post_data = array(
            's' => $word,
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
        $musicInfo = str_replace('\\', '', $result);
        $musicInfo = json_decode($musicInfo,1);
        //实例化模型
        $music = model('music/Music','model');
        //判断是否存在 否则返回null
        if(!isset($musicInfo['result']['songs'])||sizeof($musicInfo['result']['songs'])<=0){
            return null;
        }
        foreach ($musicInfo['result']['songs'] as $key => $value) {
            $id = $value['id'];
            // 歌名 歌手
            $body[$key]['title'] = $value['name'];
            $body[$key]['author'] = $value['artists'][0]['name'];
            //时间
            $time = $value['duration'];
            $body[$key]['time'] = ceil($time/1000);
            // 音乐图片
            $body[$key]['pic'] = $music->getNECSongPics($id);
            // 音乐链接
            $body[$key]['url'] = $music->getNECSongResURL($id);
            // 音乐歌词
            $body[$key]['lrc'] = $music->getNECSongLyric($id);
            //来源
            $body[$key]['origin'] = 0;
        }
        return $body;
    }
    //获取酷狗音乐搜索结果
    public function getKgSongSearch($word){
        $music =  model('music/KgMusic','model');
        $url = 'http://mobilecdn.kugou.com/api/v3/search/song?format=json&keyword='.$word.'&page=1&pagesize=10&showtype=1';
        $musicInfo = $music->curl_get($url);
        $musicInfo = json_decode($musicInfo,1);
        //判断是否存在 否则返回null
        if(!isset($musicInfo['data']['info'])||sizeof($musicInfo['data']['info'])<=0){
            return null;
        }
        foreach ($musicInfo['data']['info'] as $key => $value) {
            $mid = $value['hash'];
            // 歌名 歌手
            $body[$key]['title'] = $value['songname'];
            $body[$key]['author'] = $value['singername'];
            //时间
            $body[$key]['time'] = $value['duration'];
            //歌曲URL
            $body[$key]['url'] = $music->getKgSongResURL($value['hash']);
            //歌曲PIC
            $body[$key]['pic'] = $music->getKgSongPic($value['hash']);
            //歌曲LRC
            $body[$key]['lrc'] = $music->getKgSongLrc($value['hash']);
            //来源
            $body[$key]['origin'] = 2;
        }
        return $body;
    }
}
