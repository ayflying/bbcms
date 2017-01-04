<?php
namespace app\common\controller;
use think\Controller;
use think\Cache;
use think\Db;

class Common extends Controller{
	public $uid;
	//public $settings;
    public $_G;
	
	public function _initialize(){
        //检测当前用户UID
		$this -> uid = cookie_decode('uid') > 0 ? cookie_decode('uid') : 0;
		$user = Db::name('member_user') ->cache('user_'.$this->uid) -> find($this -> uid);
        $this -> authority($user['gid']);      //权限检测
        
		if(!cache('settings')){
			$list = db('system_settings') -> select();
			foreach($list as $val){
				$settings[$val['name']] = $val['value'];
			}
			Cache::set('settings',$settings);
		}
		//$settings = Cache::get('settings');
        $this -> _G = [
            'system' => Cache::get('settings'),
        ];
        
		$this -> assign("_G",$this -> _G);
		//dump($this -> settings);
		
		
	}
	
	public function theme($name=null){
		//return $this;
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
    //
    /*
        覆盖系统fetch方法
        该方法会自动检测当前主题的模板是否存在
	*/
    //
    public function fetch($file = '', $vars = [],$replace = [], $config = []){
        $config = array_merge(config('template'),$config);
		//$config['view_suffix'] = 'abc';
		$dir = $config['view_path'].DS.$file.'.html';
		if(!file_exists($dir)){
			 $config['view_path'] = ROOT_PATH .'template'.DS.'default'.DS;
             $this -> view -> engine($config);
		}
        return $this -> view -> fetch($file,$vars,$replace,$config);
	}

}