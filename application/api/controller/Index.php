<?php

/*
*HTTPAPI接口
*处理app提交的交互请求
*所有返回格式为JSON
*所有请求参数为POST传递
*_initialize方法为Thinkphp自带方法，用法类似构造方法，具体请查阅Thinkphp5手册
*/
namespace app\api\controller;
use think\Controller;

class Index extends Controller
{
	private $key;
    private $return;
    public $RequestType;//模块名称
    public $Interface;//控制器名称
    public $Operation;//方法名称
    /*
     *Api请求入口
     *RequestType为请求模块(模块名称)
     *Interface为控制器(控制器名称)
     *Operation(方法名称)
     */
    public function _initialize(){
        //调用请求检测逻辑层
        $CheckRequestType = \think\Loader::model('Start','logic');
        //检测结果赋值到return
        $this->return = $CheckRequestType->CheckRequestType();
        //模块
        $this->RequestType = $CheckRequestType->RequestType;
        //控制器
        $this->Interface = $CheckRequestType->Interface;
        //方法
        $this->Operation = $CheckRequestType->Operation;
    }

    //接口开始方法
    public function index(){
        // 跨域使用
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        //判断有没有错误
        if($this->return['ResultCode']==1){
            return json($this->return);
        }
        //用户检测
        $user = model('api/User','model');
        $user->checkUser();
        //实例化接口  调用service
        $service = \think\Loader::model($this->RequestType.'/'.$this->Interface,'service');
        //执行$Operation操作方法
        $action = $this->Operation;
        //返回JSON DATA
        return json($service->$action());
    }
}