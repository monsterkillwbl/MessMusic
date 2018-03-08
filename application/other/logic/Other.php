<?php
namespace app\other\logic;
use think\Model;

class Other extends Model{
    //解析控制器内方法
    public function ControllerAnalyze($controller){
        if($controller=='01'){return 'Other';}//控制器
        return false;
    } 
    //解析操作方法
    public function ActionAnalyze($action){
        if($action=='11'){return 'getOneApi';}//获取ONE 每日一篇文字
        if($action=='12'){return 'getOneAllApi';}//获取最近一周的每日一篇文字

        return false;
    }
    //检查请求数据
    public function CheckParam($action){
    }
}
