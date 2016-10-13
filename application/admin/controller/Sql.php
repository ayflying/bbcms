<?php
namespace app\admin\controller;
use think\Cache;
use think\Config;
use think\Db;

class Sql extends Common
{
    
    public function index(){
        
        if(request()->isPost()){
            $sql = input('post.sql');
            $this -> assign('sql', $sql);
            $this -> assign('return', $this -> sql($sql));
        }
        return $this -> fetch('./sql');
    }
    
    /*
        执行sql语句
    */
    public function sql($sql)
    {
        $arr = explode(';',$sql);
        $return = 0;
        foreach($arr as $val){
            if(!empty($val)){
                try{
                    $return += Db::execute($val);
                }catch(\Exception $e){
                    return "error:".$e -> getMessage();
                }
            }
        }
        return $return;
    }
}