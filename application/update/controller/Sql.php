<?php
namespace app\update\controller;
use think\Cache;
use think\Config;
use think\Request;
use think\Controller;

class Sql extends Controller
{
    public function index(){
        
        $v= Request::instance()->post('version');
        if(empty($v)){
			$this -> error("版本不存在");
		}
        $version = Config::get('version');
        
        //列出sql文件目录，显示出哪些可更新
		$dir = './application/update/sql/';
		$filelist = scandir($dir);
		unset($filelist[0]);
		unset($filelist[1]);
		
        foreach($filelist as $val){
			$sql_list[] = basename($dir.$val,".sql");
		}
        
        
        foreach($sql_list as $k => $val){
			
            //echo $v.' < '.$val.' && '.$version.' >= '.$val.'<br/>';
			if($v < $val && $version >= $val){
				$file = $dir.$val;
                echo file_get_contents($file.'.sql');
				//echo $this -> sql($k);
			}
		}
        
    }
    
    
}