<?php
namespace app\member\controller;
use think\Controller;
use think\facade\Cache;
use think\facade\Config;
use think\Db;
use think\facade\Hook;
use app\common\controller\Common as Common2;

class Common extends Common2{
    
    
    //初始化用户登录
    public function initialize(){
        parent::initialize();
        
        if(empty($this -> uid)){
            $this -> error("授权失败",null,null,1);
        }
        
    }
    
    
}