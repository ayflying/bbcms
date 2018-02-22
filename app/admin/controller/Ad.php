<?php
namespace app\admin\controller;
use think\Cache;
use think\Config;
use think\Db;

class Ad extends Common
{
    
    public function ad_list(){
        
       
        $list = Db::name('operate_ad') -> select();
        $this -> assign('list',$list);
        return $this -> fetch('./operate_ad_list');
    }
    
    public function ad_add(){
        
        if(request()->isPost()){
            $post = input('post.');
            $post['start_time'] = strtotime($post['start_time']);
            $post['end_time'] = strtotime($post['end_time']);
            Db::name('operate_ad') -> insert($post);
            $this -> success("添加完成",'ad_list');
            
        }else{
            return $this -> fetch("./operate_ad_edit");
        }
        
        
    }
    
    public function ad_edit($id){
        
        if(request()->isPost()){
            $post = input('post.');
            $post['start_time'] = strtotime($post['start_time']);
            $post['end_time'] = strtotime($post['end_time']);
            Db::name('operate_ad') -> where('id',$id) -> update($post);
            $this -> success("修改完成");
            
        }else{
            $sql = Db::name('operate_ad') -> find($id);
            $this -> assign('sql',$sql);
            
            return $this -> fetch('./operate_ad_edit');
        }
        
    }
    
    public function ad_del($id){
        
        Db::name('operate_ad') -> where('id',$id) -> delete();;
        return $this -> success("删除完成");
    }
}