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
        
		$c = config('root_namespace');
		dump($c);
		
		//$class   =  "Addons\\{$name}\\{$name}Addon";
		$class = 'addons\\'.$name.'\\'.$name;
		//$class = "addons\\ceshi\\ay";
		$addon   = new $class();
		return $addon->$tag($params);
		
    }
}
