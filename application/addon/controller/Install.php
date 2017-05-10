<?php
namespace app\addon\controller;
use app\addon\controller\Common;
use think\Db;

class Install extends Common
{
	/**
	 * 安装某个插件
	 * @param string $name 插件名称
	 * @param Mixed $params 传入的参数
	 * @return void
	 */
    public function install($name){
        $dir = ADDON_PATH . $name;
        $data = include($dir.DS.'config.php');
        if(!empty($data['setting'])){
            $data['settings'] = json_encode($data['settings']);
        }
        //进入安装
        $class = "addons\\{$name}\\Install";
        if(class_exists($class)){
            $addon   = new $class();
            if( method_exists($addon,'install') ){
                $addon->install();
            }
        }
        
        
        Db::name('addon') -> strict(false) -> insert($data);
        $this -> success("安装完成",null,null,1);
    }
    
    /**
    *   插件卸载
    *
    */
    public function uninstall($id){
        $dir = Db::name('addon') -> where('id',$id) -> value('directory');
        
        //进入卸载
        $class = "addons\\{$dir}\\Install";
        if(class_exists($class)){
            $addon   = new $class();
            if( method_exists($addon,'uninstall') ){
                $addon->uninstall();
            }
        }
		
        Db::name('addon') -> where('id',$id) -> delete();
        $this -> success("卸载完成",null,null,1);
    }
    
}
