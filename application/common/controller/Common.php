<?php
namespace app\common\controller;
use think\Controller;
use think\View;

class Common extends Controller{
	public $uid;
	public $settings;
	
	public function _initialize(){
		$this -> uid = cookie_decode('uid');
		
		if(!cache('settings')){
			$list = db('system_settings') -> select();
			foreach($list as $val){
				$settings[$val['name']] = $val['value'];
			}
			cache('settings',$settings);
		}
		$this -> settings = cache('settings');
        $_G = [
            'system' => $this -> settings,
        ];
        
		$this -> assign("G_system",$this -> settings);
		//dump($this -> settings);
		
		
	}
	
	public function theme($name=null){
		
		
		//return $this;
	}
	
	public function fetch2($file=null){
		//echo $template;
		
		$template = config('template');
		//$template['view_suffix'] = 'abc';
		$dir = $template['view_path'].$this -> settings['theme'].'/'.$file.'.html';
        
		if(!file_exists($dir)){
			 $template['view_path'] = ROOT_PATH .'template'.DS.'default'.DS;
             //$this -> engine(['view_path' => './template/default/']);
             
		}
		
        return $this -> fetch($file,[],[],$template);
	}

}