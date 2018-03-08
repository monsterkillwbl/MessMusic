<?php
namespace app\api\service;
use think\Model;

class Api extends Model {

    public function getApi(){
        $post = input('post.');
        return $post;
    }

}