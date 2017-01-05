<?php
namespace app\member\controller;
use think\Db;

use app\common\controller\Common;
use app\admin\controller\Login as AdminLogin;

class Login extends Common{
	
	public function login(){
		if (request()->isPost()){
			$post = input('post.');
			$where['username|email|mobile'] = $post['username'];
			$where['password'] = md5($post['password']);
			if($sql = Db::name('MemberUser') -> where($where) -> find()){
					//调用验证方法
					$auto = new AdminLogin();
					$uid = $auto -> authorization($sql['uid']);
					cookie('uid',$uid,2592000);
                    $from = cookie('from') ? cookie('from') : '/';
                    cookie('from',null);
				return $this -> success("登录成功",$from,null,1);
				
			}else{
				return $this -> error("帐号或者密码错误！");
			}
			
		}else{
			return $this->fetch('member/login');
		}
		
	}
}