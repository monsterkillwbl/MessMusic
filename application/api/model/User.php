<?php
namespace app\api\model;
use think\Model;

class User extends Model{
	
    // 设置当前模型对应的完整数据表名称
    protected $table = 'api_user';
    //自动写入时间
    protected $autoWriteTimestamp = true;
    
    public function checkUser(){
    	//获取请求参数
        $post = input('post.');
    	$where['OpenId'] = $post['OpenId'];
    	//查询用户是否存在
        $result = $this->where($where)->select();
        //不存在创建用户 否则增加使用次数
        if(!$result){
            $u['OpenId'] =  $post['OpenId'];
            $u['secret'] = md5($post['OpenId']);
            $u['nums'] = 1;
            $this->create($u);
        }else{
            $this->where($where)->setInc('nums');
        }
        //查询用户ID
        $user_result = $this->where($where)->find();
        //执行增加使用API记录次数
        $api = model('api/Api','model');
        $api->addCodeUseNums($user_result['id'],$post['TransCode']);
    }
}
