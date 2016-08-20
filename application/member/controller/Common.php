<?php
namespace app\member\controller;
use think\Controller;

class Common extends Controller
{
	public $uid;
	public $settings;
	
	public function _initialize(){
		$this -> uid = cookie_decode('uid');
		//$guid = cookie('guid');
		//$guid_decode = md5($_SERVER['SERVER_NAME'].cookie('uid').config('database.password'));
		if(empty($this -> uid)){
			cookie('uid',null);
			cookie('from',$_SERVER['REQUEST_URI']);
			return $this->error('未登录!页面跳转中...',url('/member/login/login'));
		}else{
			if(!cache('settings')){
				$list = think\Db::name('system_settings') -> select();
				foreach($list as $val){
					$settings[$val['name']] = $val['value'];
				}
				cache('settings',$settings);
			}
			$this -> settings = cache('settings');
			$this -> assign('G_',$this -> settings);
			/*
			if(!cache('settings')){
				$list = db('system_settings') -> select();
				foreach($list as $val){
					$settings[$val['name']] = $val['value'];
				}
				cache('settings',$settings);
			}
			$this -> settings = cache('settings');
			$this -> assign('G_',$this -> settings);
			*/
		
		}
	}
}