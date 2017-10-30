<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

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
		
		//$user = Db::name('member_user');
		
		$sql = Db::name('member_user') -> find($uid);
		if($sql['status'] != 1 ){
			return $this -> error(lang("该账户被停用"));
		}
		//$uid = cookie_encode('uid',$uid);
        $guid = empty($sql['guid']) ? md5(time().$uid.config('database.password')) : $sql['guid'];
		cookie('username',$sql['username'],2592000);
		cookie('guid',$guid,2592000);
        
        $data = [
            'guid' => $guid,
            'update_time' => time(),
            'update_ip'=>request()->ip(),
        ];
        Db::name('member_user') -> where('uid',$uid) -> update($data);
		return $guid;
	}
	
	
	function login(){
		
		if(cookie('guid')){
			//return $this -> success("已登陆，正在跳转！",url('/admin'));
			header("Location:".url('/admin')); 
			exit;
		}
		
		
		if(request()->isPost()){
			$post = input('post.');
			if(empty($post['username']) || empty($post['password'])){
				$this -> error(lang("用户名或者密码为空"));
			}else{
				
                
                $where = [
                    ['email|username|mobile', 'like', $post['username']],
                    ['password','like',md5($post['password'])],
                ];
                
                //抛出错误，开始登录
                if($sql = Db::name('member_user') -> where($where) -> find()){
					//调用验证方法
                    
					$guid = $this -> authorization($sql['uid']);
					
					//guid为管理员登录的重要标识，结构为md5(域名,加密的uid，数据库密码)三部分组成
					$admin_guid = md5($_SERVER['SERVER_NAME'].$guid.config('database.password'));
					cookie('admin_guid',$admin_guid,0);
					return $this->success(lang('登陆成功'),cookie('from'),'',1);
				}else{
					return $this -> error(lang("用户名或者密码错误"));
				}
			}
		}else{
			return $this-> fetch('./login');
		}
	}
	
	/**/
	function out(){
		//cookie('uid',null);
		cookie('admin_guid',null);
		return $this -> success("登出成功",'admin/login/login',null,1);
	}
	
	function ceshi(){
		echo 123123;
	}
}