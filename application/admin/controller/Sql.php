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
        $sql = str_replace("\r\n", "", $sql);
        $sql = preg_replace('/--(.*?)---/', '', $sql);
        $prefix = Config::get('database.prefix');
        $sql = str_replace('bb_', $prefix, $sql);
        
        $sql_arr = explode(';',$sql);
        $return = 0;
        foreach($sql_arr as $val){
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
	
	/**
     * 优化表
     * @param  String $tables 表名
     */
    public function optimize($tables = null){
		//超时一小时
		set_time_limit(3600); 
        //修复所有表
		$db = Db::query("show tables");
		$prefix = config('database.prefix');
		foreach($db as $val){
			$table = reset($val);
			//echo substr_count($temp,'u');
			if(substr_count($table,$prefix) > 0){
				//$list[] = reset($val);
				$list[] = Db::execute("OPTIMIZE TABLE `{$table}`");
			}
		}
		$this -> success('优化表完成',null,null,1);
    }
	
	/**
     * 修复表
     * @param  String $tables 表名
     */
    public function repair($tables = null){
		//超时一小时
		set_time_limit(3600); 
        //修复所有表
		$db = Db::query("show tables");
		$prefix = config('database.prefix');
		foreach($db as $val){
			$table = reset($val);
			//echo substr_count($temp,'u');
			if(substr_count($table,$prefix) > 0){
				//$list[] = reset($val);
				$list[] = Db::execute("REPAIR TABLE `{$table}`");
			}
		}
		$this -> success('修复表完成',null,null,1);
		
	}
}