<?php
namespace app\addon\controller;
use app\common\controller\Common as Common2;

class Common extends Common2{
//class Common{
	
    protected function fetch2($file){
        $this -> view -> fetech("./addons/".$file.'.html');
    }
    
    
    function load_config($name){
        
    }
}
