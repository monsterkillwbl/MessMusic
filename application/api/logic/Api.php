<?php
namespace app\api\logic;
use think\Model;

class Api extends Model{
//解析控制器内方法
    public function ControllerAnalyze($controller){
        if($controller=='01'){return 'Api';}//控制器
        return false;
    } 
    //解析操作方法
    public function ActionAnalyze($action){
        if($action=='01'){return 'getApi';}//操作方法
        return false;
    }
    //检查数据
    public function CheckParam($action){
        $post = input('post.');
        if(!isset($post['OpenId']) || empty($post['OpenId'])){return ['ResultCode'=>0,'ErrCode'=>'0000','ErrMsg'=>'OpenId not exists'];}
    }
}
