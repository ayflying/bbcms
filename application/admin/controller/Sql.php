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
		$this -> success('优化完成',null,null,1);
		
		
		/*
		if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");

                if($list){
                    $this->success("数据表优化完成！");
                } else {
                    $this->error("数据表优化出错请重试！");
                }
            } else {
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'优化完成！");
                } else {
                    $this->error("数据表'{$tables}'优化出错请重试！");
                }
            }
        } else {
            $this->error("请指定要优化的表！");
        }
		*/
    }
}