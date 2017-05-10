<?php
namespace app\addon\controller;
use app\addon\controller\Common;
use think\Db;
use think\Config;

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
        
        empty($data['settings']) or $data['settings'] = json_encode($data['settings']);
        
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
    
    public function update($name){
        
        $dir = ADDON_PATH . $name;
        $data = include($dir.DS.'config.php');
        
        if(!empty($data['settings']) ){
            $settings = Db::name('addon') -> where('directory',$name) -> value('settings');
            $settings = json_decode($settings,true);
            $data['settings'] = json_encode(array_merge($settings,$data['settings']));
        }
        //进入更新
        $class = "addons\\{$name}\\Install";
        if(class_exists($class)){
            $addon   = new $class();
            if( method_exists($addon,'update') ){
                $addon->update();
            }
        }
        
        //更新插件钩子
        $config = get_addon_config($name);
        $tag = $config['tag'];
        $tags = include(APP_PATH.'tags.php');
        $tag_arr = Config::get('tags');
        foreach($tag as $val){
            if(array_search($val,$tag_arr)){
                $tags[$val][$name] = "addons\\{$name}\\Tag";
            }
        }
        $str = "<?php\nreturn ".var_export($tags,true).";";
        file_put_contents(APP_PATH.'tags.php', $str);
        
        Db::name('addon') -> strict(false) -> where('directory',$name) -> update($data);
        $this -> success("更新完成",null,null,1);
        
    }
}
