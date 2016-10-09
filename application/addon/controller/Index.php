<?php
namespace app\addon\controller;
use app\common\controller\Common;

class Index extends Common{
	/**
	 * 执行某个插件
	 * @param string $name 插件名称
	 * @param Mixed $params 传入的参数
	 * @return void
	 */
    public function index($name,$tag='run',$params=NULL)
    {
        /*
		$c = config('root_namespace');
		dump($c);
		
		//$class   =  "Addons\\{$name}\\{$name}Addon";
		//$class = 'addons\\'.$name.'\\'.$name;
        */
        //配置模板参数
        
        $template = [
            'view_path' => 'addons' . DS . $name . DS,
            'view_replace_str' => './addons/'.$name,
            'view_replace_str' => [
                '__tpl2__' => './addons/'.$name,
            ],
            'layout_on'     =>  true,
            'layout_name'   =>  './template/default/common/addon.html',
            
        ];
        $template = array_merge(config('template'),$template);
        $this -> engine($template);
        
        //$this->view->engine->layout('./template/default/common/addon.html');
        $this -> assign('header',VIEW_PATH."common/header.html");
        $this -> assign('footer',VIEW_PATH."common/footer.html");
        
        
        
		$class = "addons\\{$name}\\index";
        $addon   = new $class();
		return  $addon->$tag($params);
		
    }
    
    function load_config($name){
        
        
    }
}
