<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Common extends Controller{
    public $uid;
	
	public function _initialize()
    {
		//$uid = cookie_decode('uid');
        $guid = cookie('guid');
        $admin_guid = cookie('admin_guid');
        
        if(!empty($guid) && !empty($admin_guid)){
            
            $guid = cookie('guid');
            $this -> uid = Db::name('member_user') -> where('guid',$guid) -> value('uid');
            //$guid_decode = md5($_SERVER['SERVER_NAME'].cookie('uid').config('database.password'));
            
            $admin_guid = md5($_SERVER['SERVER_NAME'].$guid.config('database.password'));
            if(cookie('admin_guid' == $admin_guid)){
                return;
            }
		}
        
        cookie('guid',null);
        cookie('from',$_SERVER['REQUEST_URI']);
        return $this->error(lang('未登录！正在跳转到登录页面'),'admin/login/login');
        
	}
    
	
}