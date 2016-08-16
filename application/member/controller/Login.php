<?php
namespace app\member\controller;
use think\Controller;
use app\admin\controller\Login as common_login;
//use app\member\controller\Common;

class Login extends Controller
{
	
	public function login(){
		/*
		if(cookie('uid')){
				//return $this -> success("已登陆，正在跳转！",url('/admin'));
				header("Location:".url('/')); 
				exit;
		}
		*/
		
		//Login::ceshi();
		
		if (request()->isPost()){
			$db = db("MemberUser");
			$where['uid|email|uname'] = input('post.email');
			$where['pword'] = md5(input('post.pword'));
			if($sql = $db -> where($where) -> find()){
					//调用验证方法
					$auto = new \app\admin\controller\Login();
					$uid = $auto -> authorization($sql['uid']);
					cookie('uid',$uid,2592000);
					//$uid = $this -> authorization($sql['uid']);
				return $this -> success("登录成功",cookie('from'),'',1);
			}else{
				return $this -> error("帐号或者密码错误！");
			}
			
		}else{
		
			return $this->fetch('member/login');
		}
		
	}
}