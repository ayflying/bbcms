<?php
namespace app\common\controller;
use think\Controller;

class Common extends Controller{
	public $uid;
	public $settings;
	public $luoe;
	
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
		$this -> assign('_G',$_G);
		//dump($this -> settings);
		
		
	}
	
	public function theme($name=null){
		
		
		//return $this;
	}
	
	public function fetch2($file=null){
		//echo $template;
		
		$template = config('template');
		$template['view_suffix'] = 'abc';
		$dir = $template['view_path'].$this -> settings['theme'].'/'.$file.'.html';
		if(file_exists($dir)){
			$template['view_path'] .= $this -> settings['theme'].'/';
		}else{
			$template['view_path'] .= 'default/';
		}
		\think\Config::set('template',$template);
		
		//$view = new \think\View(config('template'));
		
		//return $this -> fetch($file,[],[],$template);
		//return $view -> fetch($file);
		return view($file);
		//return view($file,[],[],$template);
	}

}