<?php
namespace app\admin\controller;
use think\Cache;
use think\Config;
use think\Db;

class Update extends Common
{
    public function index()
    {
        if($msg = input('msg')){
            $this -> success($msg,'index',null,1);
            exit;
        }
        
        Cache::set('old_version',Config::get('version'),0);
        $url =  UPDATE_URL . "/update/list";
        $put = curl($url);
        if(empty($put)){
            return $this -> error("更新节点异常，请检查配置文件");
        }
        $put = json_decode($put,true);
        $list = [];
        foreach($put as $key => $val){
            if(!file_exists($val['dir']) || md5_file($val['dir']) != $val['md5']){
                $list[$key] = $val;
            }
        }
        file_put_contents('./update.txt',json_encode($list));
        $this -> assign('list',$list);
        $this -> assign('count',count($list));
        return $this -> fetch('./update');
    }
    
    public function download(){
        
        $list = json_decode(file_get_contents('./update.txt'),true);
        $this -> assign('list',$list);
        $this -> assign('count',count($list));
        return $this -> fetch('./update_download');
    }
    
    
    /*准备升级sql*/
    public function sql(){
        $post['version'] = Db::name('system_settings') -> where('name','version') -> value('value');
        if(empty($post['version'])){
            $post['version'] = '0.2.2016120';
        }
        $url =  UPDATE_URL . "/update/sql";
        $sql = curl($url,$post);
        
        $db = new Sql();
        $num = $db -> sql($sql);
        $this -> success("升级完成，影响数据".$num,"system/cache");
    }
    
}