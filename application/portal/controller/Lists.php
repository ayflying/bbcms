<?php
namespace app\portal\controller;
use think\Db;
use app\portal\model\PortalArticle;
use app\common\controller\Common;

class Lists extends Common{
	public function index($tid,$search=null,$order='update_time desc'){
		$sql = Db::name('portal_menu') -> find($tid);
        if(!empty($sql['jump'])){
            header('Location: '.$sql['jump']);
        }
		//$this -> assign('title',$sql['name'].' - ');
		//$this -> assign('bb',$sql);
        
		$where['status']= 1;
		
		
		if($tid > 0){
			//获取下级目录文章
			$type = Db::name('portal_menu') -> where('pid',$tid) -> column('tid');
			$type[] = (int)$tid;
			$where['tid'] = ['in',$type];
		}
		//isset($search) and $where['title'] = ['like' , '%'.$search.'%'];
		
		if($sql['mod']>0){
			$list = Db::view(['portal_article','a'],'*')
			->view(['portal_mod_'.$sql['mod'],'m'],'*','a.aid = m.aid')
			->where($where) -> order($order) -> paginate(PAGE_NUM);
		}else{
			$list = Db::name('portal_article')
			->where($where) -> order($order) -> paginate(PAGE_NUM);
		}
		$page = $list->render();
        
        $this -> _G['menu'] = $sql;
        $this -> _G['page'] = $page;
        $this -> _G['list'] = $list;
        $this -> _G['title'] = $sql['name'];
        $this -> assign('_G',$this -> _G);
        
		return $this-> fetch($this -> _G['menu']['template']);
	}
	

}