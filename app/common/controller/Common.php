<?php
namespace app\common\controller;
use think\Controller;
use think\facade\Cache;
use think\facade\Config;
use think\Db;
use think\facade\Hook;

class Common extends Controller{
	
	public $uid;
	//public $settings;
    public $_G;
	
	public function initialize(){
        //检测当前用户UID
        $user = Db::name('member_user') -> where('guid',cookie('guid')) ->cache(true) -> find();
        $this -> uid = $user['uid'] > 0 ? $user['uid'] : 0;
        $this -> authority($user['guid']);      //权限检测
        
        
        /*
		$this -> uid = cookie_decode('uid') > 0 ? cookie_decode('uid') : 0;
		$user = Db::name('member_user') ->cache('user_'.$this->uid) -> find($this -> uid);
        $this -> authority($user['uid']);      //权限检测
        */
        
		if(!cache('settings')){
			$list = Db::name('system_settings') -> select();
            foreach($list as $val){
                if($val['name'] == 'statistics'){
                    $val['value'] = htmlspecialchars_decode($val['value']);
                }
				$settings[$val['name']] = $val['value'];
			}
			Cache::set('settings',$settings);
		}
        $this -> _G = [
            'system' => Cache::get('settings'),
        ];
        $user['uid'] > 0 && $this -> _G['user'] = $user;
		//dump($this -> settings);
        
	}
	
	
    public function authority($gid){
        
        $where = [
            'module' => strtolower(request()->module()),
            'controller' => strtolower(request()->controller()),
            'action' => strtolower(request()->action()),
        ];
        //$list = Db::name('member_action') -> where('module',$where['module']) -> select();
        $group = Db::name('member_group') -> cache('group_'.$gid) -> find($gid);
        $group_arr = explode(',',$group['value']);
        $action = Db::name('member_action') -> where('id','not in',$group_arr) -> cache('action_'.$group['value']) -> select();
        //dump($group_arr);
        //dump($action);
        
        
        //dump($where);
        /*
        
        //dump($where);
        $action = db('member_action') -> where('module',$module) -> select();
        //dump($action);
        foreach($action as $val){
            //检测当前是否存在该权限设置
            if(($val['controller'] == $controller || $val['controller'] == null) && ($val['action'] == $action || $val['action'] == null)){
                //echo $val['name'];
                
                
                break;
            }
        }
        */
        
    }
    
   /**
     *  重写系统fetch方法
     *  该方法会自动检测当前主题的模板是否存在
	*/
    
    protected function fetch($file = '', $vars = [],$replace = [], $config = []){
        //$view_path = Config::get('bbcms.view_path');
        $replace = [
            '__Tpl__' => Config::get('bbcms.view_path'),
            '__PUBLIC__' => '/public',
        ];
        /*
        $config = array_merge(config('template.'),$config);
		//$config['view_suffix'] = 'abc';
		$dir = $config['view_path'] . DIRECTORY_SEPARATOR . $file.'.html';
		if(!file_exists($dir)){
			 $config['view_path'] = ROOT_PATH .'template'. DIRECTORY_SEPARATOR .'default'. DIRECTORY_SEPARATOR;
             $this -> view -> engine($config);
		}
        */
		$this -> assign('_G',$this -> _G);
        return $this -> view -> fetch($file,$vars,$replace,$config);
	}
    

}