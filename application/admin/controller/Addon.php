<?php
namespace app\admin\controller;
use think\Config;
use think\Db;

class Addon extends Common{
	public function Addon_list(){
		
		$db = Db::name('addon');
		$list = $db -> select();
		$dir = './Addons';
		$scandir = scandir($dir);
		
		foreach($scandir as $val){
			 if(!is_dir($val) && $val != '.gitignore'){
				//$dirlist[$val] = $val;
				//if(!$db -> where("directory = '$val'") -> find()){
				if(!$db -> where("directory = '$val'") -> find()){
					$list2[] = ['directory' => $val];
				}
			 }
		}
		
		$this -> assign('list',$list);
		$this -> assign('list2',$list2);
		
		return $this -> fetch('./addon_list');
	}
	
	
	
}