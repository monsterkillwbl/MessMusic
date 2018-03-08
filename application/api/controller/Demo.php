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

class Demo extends Controller
{
    public function index(){
        return view('index');
    }
}
