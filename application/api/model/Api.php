<?php
namespace app\api\model;
use think\Model;

class Api extends Model{
	
    // 设置当前模型对应的完整数据表名称
    protected $table = 'api_api';
    //自动写入时间
    protected $autoWriteTimestamp = true;

    // 增加使用API记录次数 详细到每个接口
    public function addCodeUseNums($id,$code){
    	$where['uid'] = $id;
    	$where['TransCode'] = $code;
    	//查询是否已经使用过
    	$result = $this->where($where)->select();
    	//使用过则自增一次 没有则创建
    	if(!$result){
    		$obj['uid'] = $id;
    		$obj['TransCode'] = $code;
    		$obj['nums'] = 1;
    		$this->create($obj);
    	}else{
    		$this->where($where)->setInc('nums');
    	}
    }
}
