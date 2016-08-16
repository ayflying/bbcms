<?php
namespace app\admin\controller;
use think\Controller;

class Login extends Controller{
    
	public function index(){
		
		/*
		//echo time();
		import('lib.Ceshi');
		$ceshi = new \lib\Ceshi();
		$ceshi -> index();
		*/
		
		
	}
	
	function authorization($uid){
		//S(array('expire'=>86400,'prefix'=>'user'));
		/*
		if(S($uid)){
			$user = S($uid);
			$guid = $user[$guid] ? $user[$guid] : md5($uid.time()); //加密算法
			
		}else{
			$guid =  md5($uid.time()); //加密算法
		}
		*/
		
		$user = db('MemberUser');
		/*
		//$user -> uid = $uid;
		$user -> update_time = time();
		$user -> update_ip = request()->ip();
		*/
		$user -> where(['uid' => $uid]) ->  update(['update_time' => time(),'update_ip'=>request()->ip()]);
		$sql = $user -> find($uid);
		
		if($sql['status'] != 1 ){
			return $this -> error("该账户被停用！");
		}
		$uid = cookie_encode('uid',$uid);
		cookie('uname',$sql['uname'],2592000);
		//cookie('gid','1',3600);
		
		return $uid;
	}
	
	
	function login(){
		
		
		//S(array('prefix' => 'user'));
		//$user = S(cookie('uid'));
		//多说登陆初始化
		//$system = F('system/settings');
		//$this -> assign('system',$system);
		if(cookie('guid')){
			//return $this -> success("已登陆，正在跳转！",url('/admin'));
			header("Location:".url('/admin')); 
			exit;
		}
		
		
		if(request()->isPost()){
			$post = input('post.');
			if(empty($post['email']) || empty($post['pword'])){
				$this -> error("用户名或者密码为空");
			}else{
				$db = db("MemberUser");
				$where['uid|email|uname'] = input('post.email');
				$where['pword'] = md5(input('post.pword'));
				/*
				$code = input('post.code');
				if(check_verify($code) == null){
					$this -> error("验证码错误");
				}
				*/
				//抛出错误，开始登录
				if($sql = $db -> where($where) -> find()){
					//调用验证方法
					$uid = $this -> authorization($sql['uid']);
					
					//guid为管理员登录的重要标识，结构为md5(域名,加密的uid，数据库密码)三部分组成
					$guid = md5($_SERVER['SERVER_NAME'].$uid.config('database.password'));
					cookie('guid',$guid,0);
					return $this->success('登陆成功',cookie('from'),'',1);
				}else{
					return $this -> error("用户名或者密码错误");
				}
			}
		}else{
			return $this-> fetch('./login');
		}
	}
	
	/**/
	function out(){
		cookie('uid',null);
		cookie('guid',null);
		return $this -> success("登出成功",'/admin/login/login','',1);
	}
	
	function ceshi(){
		echo 123123;
	}
}