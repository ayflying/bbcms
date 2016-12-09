<?php
namespace app\admin\controller;
use think\Config;
use think\Db;

class Menu extends Common{
	
	public function index(){
		$db = Db::name('PortalMenu');
		$sql = $db -> order('tid') ->  order('weight desc') -> select();
		//$sql = $db -> where("pid = 0") -> select();
		$this -> assign('sql',$sql);
		return $this -> fetch('./portal_menu');
	}
	
	public function menu_add(){
		
		if(request()->isPost()){
			$post = input('post.');
			empty($post['template']) and $post['template'] = './Portal/list';
			empty($post['template2']) and $post['template2'] = './Portal/article';
			Db::name('PortalMenu') -> insert($post);
			$this -> success('添加成功');
			
		}else{
			$type = Db::name('PortalMod') -> select();
			$this -> assign('type',$type);
			
			$menu = Db::name('PortalMenu') -> where(["pid" => 0]) -> select();
			$this -> assign('menu',$menu);
			
			$this -> assign('sql');
			return $this-> fetch('./portal_menu_edit');
		}
		
	}
	
	function menu_edit($tid){
		
		$type_db = Db::name('PortalMod');
		$type = $type_db -> select();
		$this -> assign('type',$type);
		
		$menu_db = Db::name('PortalMenu');
		$menu = $menu_db -> where(["pid" => 0]) -> select();
		$this -> assign('menu',$menu);
		
		$sql = $menu_db -> find($tid);
		$this -> assign('sql',$sql);
		
		if(request()->isPost()){
			$post = input('post.');
			//print_r(input('post.'));
			//$menu_db -> create();
			$save = $post;
			if(empty($post['template'])){
				//$menu_db -> template = './Portal/list';
				$save['template'] = './portal/list';
			}
			if(empty($post['template2'])){
				//$menu_db -> template2 = './Portal/article';
				$save['template2'] = './portal/article';
			}
			//默认值
			
			$menu_db -> where('tid',$tid) -> update($save);
			if($post['pid'] > 0){
				$menu_db -> where('pid',$tid) -> setField('pid',$post['pid']);	//修改下级栏目
			}
			
			return $this -> success('编辑成功');
			
		}else{
			return $this-> fetch('./portal_menu_edit');
		}
	}
    
    public function menu_delete($tid){
        
        $pid = Db::name('portal_menu') -> where("pid",$tid) -> find();
        if(isset($pid)){
            $this -> error("请先删除下级栏目再删除该栏目");
        }
        
        Db::name('portal_menu') -> delete($tid);
        $aid = Db::name('portal_article') -> where('tid',$tid) -> column('aid');
        $portal = new Portal;
        foreach($aid as $val){
            $portal -> delete($val);
        }
        return $this -> success("删除完成",null,null,1);
    }
	
}