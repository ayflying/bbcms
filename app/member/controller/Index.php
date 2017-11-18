<?php
namespace app\member\controller;
use Think\Db;
use app\member\controller\Common;

class Index extends Common{
    public function index()
    {
        $uid = $this -> uid;
        $user = Db::name('member_user') -> where('uid',$uid) -> find();
        $this -> _G['user']  = $user;
        return $this -> fetch('./member/index');
        
    }
    
    public function member(){
        
    }
}
