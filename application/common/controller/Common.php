<?php
namespace app\common\controller;
use think\Controller;
use think\Cache;

class Common extends Controller{
	public $uid;
	//public $settings;
    public $_G;
	
	public function _initialize(){
        //检测当前用户UID
		$this -> uid = cookie_decode('uid') > 0 ? cookie_decode('uid') : 0;
		
        $this -> authority();      //权限检测
        
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
	
    public function authority(){
        
        $where = [
            'module' => strtolower(request()->module()),
            'controller' => strtolower(request()->controller()),
            'action' => strtolower(request()->action()),
        ];
        //dump($where);
        /*
        $module = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action = strtolower(request()->action());
        
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