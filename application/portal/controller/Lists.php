<?php
namespace app\portal\controller;
use think\Db;
use app\portal\model\PortalArticle;
use app\common\controller\Common;

class Lists extends Common{
	public function index($tid=0,$search=null,$order='update_time desc'){
		$sql = Db::name('portal_menu')-> cache("menu_".$tid) -> find($tid);
        if(!empty($sql['jump'])){
            header('Location: '.$sql['jump']);
        }
        
		$where['status']= 1;
		
		
		if($tid > 0){
			//获取下级目录文章
			$type = Db::name('portal_menu') -> where('pid',$tid) -> cache(true) -> column('tid');
			$type[] = (int)$tid;
			$where['tid'] = ['in',$type];
		}
		//isset($search) and $where['title'] = ['like' , '%'.$search.'%'];
		$table_article = 'portal_article';
		if($sql['mod']>0){
			$table_mod = 'portal_mod_'.$sql['mod'];
            //$list = Db::view(['portal_article'=>'a'],'*')
			$list = Db::view($table_article)
            ->view($table_mod,'*',$table_article.'.aid = '.$table_mod.'.aid')
			->where($where) -> order($order) -> cache(true,null,'list') -> paginate(PAGE_NUM);
		}else{
			$list = Db::name('portal_article')
			->where($where) -> order($order) -> cache(true,null,'list') -> paginate(PAGE_NUM);
		
        }
		$page = $list->render();
        $this -> _G['menu'] = $sql;
        $this -> _G['page'] = $page;
        $this -> _G['list'] = $list;
        
        
		return $this-> fetch($this -> _G['menu']['template_list']);
	}
	

}