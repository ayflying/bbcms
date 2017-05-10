<?php
namespace app\addon\controller;
use app\common\controller\Common as Common2;

class Common extends Common2{
//class Common{
	
    /**
     *  重写系统fetch方法
     *  该方法会自动检测当前主题的模板是否存在
	*/
    protected function fetch($file = '', $vars = [],$replace = [], $config = []){
        $this -> assign('_G',$this -> _G);
        return $this -> view -> fetch("addons".DS.input('name').DS.$file.'.html');
    }
    
    
    protected function addon_config($name=null){
        empty($name) and $name = input('name');
        return include("./addons".DS.$name.DS.'config.php');
    }
}
