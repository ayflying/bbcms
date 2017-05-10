<?php
namespace app\addon\controller;
use app\addon\controller\Common;
use think\Db;

class Start extends Common
{
	/**
	 * 启动插件
	 * @param int $id 插件编号
	 * 
	 *
	 */
    public function start($id)
    {   
        $sql = Db::name('addon') -> field('status,directory') -> where('id',$id) -> find();
        $name = $sql['directory'];
        $config = get_addon_config($name);
        $tag = $config['tag'];
        $tags = include(APP_PATH.'tags.php');
            
        if($sql['status'] != 1){
            
            //注册插件钩子
            foreach($tag as $val){
                if(isset($tags[$val])){
                    $tags[$val][$name] = "addons\\{$name}\\Tag";
                }
            }
            $str = "<?php\nreturn ".var_export($tags,true).";";
            file_put_contents(APP_PATH.'tags.php',$str);
            Db::name('addon') -> where('id',$id) -> update(['status' => 1]);
            $this -> success("启动完成",null,null,1);
        }else{
            
            //取消插件钩子
            foreach($tag as $val){
                if(isset($tags[$val])){
                    unset($tags[$val][$name]);
                }
            }
            //文件写入
            $str = "<?php\nreturn ".var_export($tags,true).";";
            file_put_contents(APP_PATH.'tags.php',$str);
            Db::name('addon') -> where('id',$id) -> update(['status' => 0]);
            $this -> success("关闭完成",null,null,1);
        }
    }
    
}
