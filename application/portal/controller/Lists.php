<?php
namespace app\portal\controller;
use think\Db;
use app\portal\model\PortalArticle;
use app\common\controller\Common;

class Lists extends Common{
	public function index($tid,$search=null,$order='update_time desc'){
		$sql = Db::name('portal_menu') -> find($tid);
		$this -> assign('title',$sql['name'].' - ');
		$this -> assign('bb',$sql);
		$where['status']= 1;
		
		
		if($tid > 0){
			//获取下级目录文章
			$type = Db::name('portal_menu') -> where('topid',$tid) -> column('tid');
			$type[] = (int)$tid;
			$where['tid'] = ['in',$type];
		}
		isset($search) and $where['title'] = ['like' , '%'.$search.'%'];
		
		if($sql['mod']>0){
			//$mod = Db::name('portal_mod') -> field('table') -> find($sql['mod']);
			//echo 'portal_mod_'.$mod['table'];
			$list = Db::view(['portal_article','a'],'*')
			//->view('portal_mod_'.$mod['table'],'*','portal_article.aid = portal_mod_'.$mod['table'].'.aid')
			->view(['portal_mod_'.$sql['mod'],'m'],'*','a.aid = m.aid')
			->where($where) -> order($order) -> paginate(5);
		}else{
			$list = Db::name('portal_article')
			->where($where) -> order($order) -> paginate(5);
		}
		$page = $list->render();
		$this -> assign('page',$page);
		$this -> assign('list',$list);
		
		/*
		$page = Db::name('portal_article') -> field('aid')-> where($where) -> order($order) -> paginate(25);
		foreach($page as $val){
			$aid[] = $val['aid'];
		}
		// 获取分页显示
		$page = $page->render();
		$this -> assign('page',$page);
		if(isset($aid[0])){
			$list = PortalArticle::all($aid,["addonarticle","attachment"]);
			
		}
		$this -> assign('list',isset($list) ? $list : null);
		*/
		
		
		return $this-> fetch('portal/list');
	}
	

}