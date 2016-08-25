<?php
namespace app\admin\controller;
use think\Controller;

class Common extends Controller{
    public $uid;
	
	public function _initialize(){
		$uid = cookie_decode('uid');
		$this -> uid = $uid;
		$guid = cookie('guid');
		$guid_decode = md5($_SERVER['SERVER_NAME'].cookie('uid').config('database.password'));
		if(empty($guid) || empty($uid) || $guid != $guid_decode){
			cookie('guid',null);
			cookie('from',$_SERVER['REQUEST_URI']);
			return $this->error('未登录!页面跳转中...','admin/login/login');
		}else{
		
		}
	}
	
}