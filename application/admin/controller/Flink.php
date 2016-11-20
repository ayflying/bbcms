<?php
namespace app\admin\controller;
use think\Cache;
use think\Config;
use think\Db;

class Flink extends Common
{
    
    public function flink_list(){
        
       
        $list = Db::name('operate_flink') -> select();
        $this -> assign('list',$list);
        return $this -> fetch('./operate_flink_list');
    }
    
    public function flink_add(){
        
        if(request()->isPost()){
            $post = input('post.');
            Db::name('operate_flink') -> insert($post);
            $this -> success("添加完成",'flink_add');
            
        }else{
            return $this -> fetch("./operate_flink_edit");
        }
        
        
    }
    
    public function flink_edit($id){
        
        if(request()->isPost()){
            $post = input('post.');
            Db::name('operate_flink') -> where('id',$id) -> update($post);
            $this -> success("修改完成");
            
        }else{
            $sql = Db::name('operate_flink') -> find($id);
            $this -> assign('sql',$sql);
            
            return $this -> fetch('./operate_flink_edit');
        }
        
    }
    
    public function flink_del($id){
        Db::name('operate_flink') -> where('id',$id) -> delete();;
        return $this -> success("删除完成");
    }
}