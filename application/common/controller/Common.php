<?php
namespace app\common\controller;
use think\Controller;
use think\Cache;

class Common extends Controller{
	public $uid;
	//public $settings;
    public $_G;
	
	public function _initialize(){
		$this -> uid = cookie_decode('uid');
		
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
	
	public function fetch2($file = null, $vars = [],$replace = [], $config = []){
		$config = array_merge(config('template'),$config);
		//$config['view_suffix'] = 'abc';
		$dir = $config['view_path'].DS.$file.'.html';
		if(!file_exists($dir)){
			 $config['view_path'] = ROOT_PATH .'template'.DS.'default'.DS;
             //$this -> engine(['view_path' => './template/default/']);
		}
		
        return $this -> fetch($file,$vars,$replace,$config);
	}

}