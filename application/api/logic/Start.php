<?php
namespace app\api\logic;
use think\Model;

class Start extends Model{
    public $RequestType;//模块名称
    public $Interface;//控制器名称
    public $Operation;//方法名称
    //检查请求类型
    public function CheckRequestType(){
        $post = input('post.');
        //检测交易码和OpenId
        if(!isset($post['TransCode'])|| empty($post['TransCode'])){return ['ResultCode'=>1,'ErrCode'=>'0001','ErrMsg'=>'TransCode not exists'];}
        if(!isset($post['OpenId']) || empty($post['OpenId'])){return ['ResultCode'=>1,'ErrCode'=>'0000','ErrMsg'=>'OpenId not exists'];}
        //解析交易码，返回模块+控制器+方法
        $Analyze = $this->TransCodeAnalyze();
        //检测模块
        if(!isset($Analyze['Module'])){
            return $Analyze;
        }
    	$this->RequestType = $Analyze['Module'];
    	$this->Interface = $Analyze['Controller'];
    	$this->Operation = $Analyze['Action'];
    	//检测post请求
    	if(!Request()->isPost()){
            return ['ResultCode'=>1,'ErrCode'=>'0002','ErrMsg'=>'Post not through'];
        }
    	//检测RequestType
    	if(!isset($this->RequestType)){
            return ['ResultCode'=>1,'ErrCode'=>'0003','ErrMsg'=>'Module not exists'];
        }
    	//检测Interface
    	if(!isset($this->Interface)){
            return ['ResultCode'=>1,'ErrCode'=>'0004','ErrMsg'=>'Controller not exists'];
        }
		//检测OperAtion
    	if(!isset($this->Operation)){
            return ['ResultCode'=>1,'ErrCode'=>'0005','ErrMsg'=>'Action not exists'];
        }
    	//检测模块是否存在
    	if(!is_dir(APP_PATH . $this->RequestType)){
            return ['ResultCode'=>1,'ErrCode'=>'0003','ErrMsg'=>'Module:'.$this->RequestType.' not exists'];
        }

        //实例化对应模块的检测方法，注意每个模块的logic层必须有这个方法，哪怕方法内是空的
        $RequestTypelogic = \think\Loader::model($this->RequestType.'/'.$this->Interface,'logic');//调用请求检测逻辑层

        //检测结果赋值到return
        $return = $RequestTypelogic->CheckParam($this->Operation);
        if($return){
            return $return;
        }
    }

    //解析交易码
    protected function TransCodeAnalyze(){
        $TransCode = input('TransCode');        //按两位分割交易码
        $TransCodeAnalyze = str_split($TransCode, 2);
        //执行解析模块
        $InterfaceAnalyze = $this->InterfaceAnalyze($TransCodeAnalyze);
        return $InterfaceAnalyze;
    }

    //解析操作方法
    protected function InterfaceAnalyze($transcode){
        $ModuleAnalyze = false;
        // 模块列表
        if($transcode[0]=='01'){$ModuleAnalyze = 'api';}//基础链接模块
        if($transcode[0]=='02'){$ModuleAnalyze = 'music';}//网易云音乐
        if($transcode[0]=='03'){$ModuleAnalyze = 'other';}//ONE(一个)
        // 判断模块是否存在
        if(!$ModuleAnalyze){
            return ['ResultCode'=>1,'ErrCode'=>'0003','ErrMsg'=>'Module not existent'];
        }
        //实例化默认逻辑层控制器,注意每个模块logic层必须有一个与模块名一致的默认控制器，用于处理默认解析
        if(!is_dir(APP_PATH . $ModuleAnalyze)){
            return ['ResultCode'=>1,'ErrCode'=>'0003','ErrMsg'=>'Module:'.$ModuleAnalyze.' not exists'];
        }
        //调用请求检测逻辑层 加载$ModuleAnalyze模块下的logic部分$ModuleAnalyze的控制器
        $RequestTypelogic = \think\Loader::model($ModuleAnalyze.'/'.$ModuleAnalyze,'logic');
        
        //获取默认控制器内配置的实际控制器 传入交易码
        $Controller = $RequestTypelogic->ControllerAnalyze($transcode[1]);
        if(!$Controller){
            return ['ResultCode'=>1,'ErrCode'=>'0004','ErrMsg'=>'Controller not existent'];
        }
        //继续实例化操作方法
        $Action = $RequestTypelogic->ActionAnalyze($transcode[2]);

        if(!$Action){
            return ['ResultCode'=>1,'ErrCode'=>'0005','ErrMsg'=>'Action not existent'];
        }
        return ['Module'=>$ModuleAnalyze,'Controller'=>$Controller,'Action'=>$Action];
    }
    //检查请求数据
    public function CheckParam($action){
        $post = input('post.');
        if(!isset($post['OpenId']) || empty($post['OpenId'])){return ['ResultCode'=>1,'ErrCode'=>'0000','ErrMsg'=>'OpenId not exists'];}
        if(!isset($post['TransCode'])|| empty($post['TransCode'])){return ['ResultCode'=>1,'ErrCode'=>'0001','ErrMsg'=>'TransCode not exists'];}
    }
}
