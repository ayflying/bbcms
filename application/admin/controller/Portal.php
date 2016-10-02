<?php
namespace app\admin\controller;
use think\Config;
use think\Db;

class Portal extends Common{
	
	public function menu(){
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
				$save['template'] = './Portal/list';
			}
			if(empty($post['template2'])){
				//$menu_db -> template2 = './Portal/article';
				$save['template2'] = './Portal/article';
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
	

	
	/*内容列表*/
	public function article_list($tid=null,$order='update_time desc'){
		
		if(request()->isPost()){
			
		}else{
			$where = [
                'status' => 1,
            ];
            if(!empty($tid)){
                $where['tid'] = $tid;
            }
            
			$list = Db::name('PortalArticle') -> where($where)-> order($order) -> paginate(PAGE_NUM);
            // 获取分页显示
			$page = $list->render();
			//dump($list);
			$this -> assign("list",$list);
			$this->assign('page', $page);
			return $this-> fetch('./portal_article_list');
		}
	}
	
	/*内容删除*/
	function delete($aid){			
		if(is_array($aid)){
			foreach($aid as $val){
				Db::name('portal_article') -> where('aid',$val) -> setField('status',-1);
			}
		}else{
			Db::name('PortalArticle') -> where('aid',$aid) -> setField('status',-1);
		}
		$this -> success('删除成功',$_SERVER['HTTP_REFERER'],'',1);
	}
	
	
	function recycle(){//回收站
		
		$list = Db::name('portal_article') -> order('aid desc') ->where('status','<',0) -> paginate(PAGE_NUM);
		
		$page = $list->render();
		$this->assign('page', $page);
		$this -> assign('list',$list);
		return $this -> fetch('./portal_article_recycle');
	}
	
	/*
		文章回收站指令
		get.action = 1 为恢复
		get.action = 2 为删除
	*/
	public function recover($action,$aid){			//恢复 彻底删除
		if($action == 2){	//彻底删除
			if(is_array($aid)){	//判断是否为数组
				foreach($aid as $val){
					$this -> delete2($val);
				}
			}else{
				$this -> delete2($aid);
			}
			$action = "彻底删除";
		}else{	//恢复
			$db_article =  Db::name('portal_article');
			if(is_array($aid)){	//判断是否为数组
				foreach($aid as $val){
					$db_article -> where(array('aid' => $val)) -> setField('status',1);
				}
			}else{
				$db_article -> where(array('aid' => $aid)) -> setField('status',1);
			}
			$action = "恢复";
		}
		$this -> success($action.'成功');
	}
	
	/*彻底删除文章*/
	public function delete2($aid){
		//echo $aid;
		//$db_article =  Db::name('portal_article');
		//$db_addonarticle = Db::name('PortalAddonarticle');
		//$db_attachment = Db::name('PortalAttachment');
		$article = Db::name('portal_article') -> find($aid);
		
		/*
		//删除缩略图
		if($article['litpic']){
			$litpic = $article['litpic'];
			file_exists($litpic) and unlink($litpic);
		}
		*/
		$attachment = Db::name('PortalAttachment') ->field('url') -> where('aid',$aid) -> select();
		foreach($attachment as $val){
			//echo "删除：".$f."<br/>";
			file_exists($val['url']) and unlink($val['url']);
		}
		
		//删除模型表
		if($article['mod'] > 0){
			$mod = Db::name('PortalMod') -> field('table') -> find($article['mod']);
			Db::name('portal_mod_'.$mod['table']) -> delete($aid);
		}
		Db::name('portal_article') -> delete($aid);
		Db::name('PortalAddonarticle') -> delete($aid);
		Db::name('PortalAttachment') -> where('aid',$aid) -> delete();
		
		return $article['title'];
	}
	
}