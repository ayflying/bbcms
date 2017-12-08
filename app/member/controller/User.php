<?php
namespace app\member\controller;

use Think\Db;
//use app\member\controller\Common;

class User extends Common
{
    
    public function index($uid){
        if($this -> uid == $uid){
            redirect(url('/member'));
        }
        $user = Db::name('member_user') -> where('uid',$uid) -> find();
        $this -> _G['user']  = $user;
        return $this -> fetch('./member/index');
        
    }
    
}