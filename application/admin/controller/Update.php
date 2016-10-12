<?php
namespace app\admin\controller;
use think\Cache;
use think\Config;

class Update extends Common
{
    public function index()
    {
        
        Cache::set('old_version',Config::get('version'),3600);
        $url =  UPDATE_URL . "/update/index/updatelist";
        $put = curl($url);
        $put = json_decode($put,true);
        $list = [];
        foreach($put as $key => $val){
            if(!file_exists($val['dir']) || md5_file($val['dir']) != $val['md5']){
                $list[$key] = $val;
            }
        }
        file_put_contents('./update.txt',json_encode($list));
        $this -> assign('list',$list);
        return $this -> fetch('./update');
    }
    
    public function download(){
        
        //$list = json_decode(file_get_contents('./update.txt'),true);
        $list = file_get_contents('./update.txt');
        //dump($list);
        $this -> assign('list',$list);
        return $this -> fetch('./update_download');
    }
    
}