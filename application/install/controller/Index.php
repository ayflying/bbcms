<?php
namespace app\install\controller;
use \think\Controller;
use \think\Db;

class Index extends Controller
{
    public $config_path = APP_PATH.'database.php';
    
    
    
    public function _initialize()
    {
        if(file_exists('./public/install.lock')){
            $this -> error("请手动删除./public/install.lock文件再进行安装操作",'/',null,60);
        }

        
    }
    
    
    public function index()
    {
        
        
        
        return $this -> fetch('./index');
    }
    
    public function step2(){
        $file = $this -> config_path;
        $config = include($file);
        $config_str = file_get_contents($file);
        //
        //dump($config_str);
        if(request()->isPost()){
            $post = input('post.');
            //dump($post);
            foreach($post as $key => $val){
                //$old = "'{$key}' => '{$config[$key]}'";
                $old = "/[\'|\"]{$key}[\'|\"](.*?)[\'|\"]{$config[$key]}[\'|\"]/i";
                $new = "'{$key}' => '{$val}'";
                //echo $old." = ".$new."<br/>";
                $config_str = preg_replace($old, $new, $config_str, 1);
                //$config_str = str_replace($old,$new,$config_str);
            }
            
            if(is_writable($file)){
                file_put_contents($file,$config_str);
                $this-> success("保存配置文件，开始安装",'step3');
            }else{
                $this -> error($file."文件无写入权限");
            }
            
        }else{
            //dump($arr);
            $this -> assign('config',$config);
            return $this -> fetch('./step2');
        }
        
    }
    
    public function step3(){
        set_time_limit(3600);
        $config = include($this -> config_path);
        $path = APP_PATH.'install/sql/table.sql';
        //echo $path;
        $sql = file_get_contents($path);
        $sql = str_replace("\r\n","",$sql);
        $sql = preg_replace('/--(.*?)---/', '', $sql);
        $sql = str_replace('bb_',$config['prefix'],$sql);
        $sql_arr = explode(';',$sql);
        $sql_arr= array_filter($sql_arr);
        foreach($sql_arr as $val){
            //dump($val);
            Db::connect($config) -> execute($val);
        }
        file_put_contents('./public/install.lock','');
        $this -> success("程序安装完成",'/');
    }
    
}
