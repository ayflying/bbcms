<?php
namespace app\admin\controller;
use think\Config;
use think\Db;

class Portal extends Common{
	
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
            //$page_num = cookie('page_num') > 0 ? cookie('page_num') : PAGE_NUM;
			$list = Db::name('PortalArticle') -> where($where)-> order($order) -> paginate(PAGE_NUM);
            // 获取分页显示
			$page = $list->render();
			//dump($list);
			$this -> assign("list",$list);
			$this->assign('page', $page);
			return $this-> fetch('./portal_article_list');
		}
	}
	
	/*内容软删除*/
	function remove($aid){			
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
		//$page_num = cookie('page_num') > 0 ? cookie('page_num') : PAGE_NUM;
		$list = Db::name('portal_article') -> order('aid desc') ->where('status',-1) -> paginate(PAGE_NUM);
		
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
	public function recover($action,$aid=null){			//恢复 彻底删除
        isset($aid) or $this -> error("请选择需要删除内容");
        if($aid == 0){
            $aid = Db::name('portal_article') -> where('status',-1) -> column('aid');
        }
		if($action == 2){	//彻底删除
			if(is_array($aid)){	//判断是否为数组
				foreach($aid as $val){
					$this -> delete($val);
				}
			}else{
				$this -> delete($aid);
			}
			$action = "删除";
		}elseif($action == 1){	//恢复
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
		$this -> success($action.'完成');
	}
	
	/*物理删除文章*/
	public function delete($aid){
         //设置为永久执行不超时
         set_time_limit(3600);
         
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
			file_exists($val['url']) && unlink($val['url']);
		}
		
		//删除模型表
		if($article['mod'] > 0){
			Db::name('portal_mod_'.$article['mod']) -> delete($aid);
		}
		Db::name('portal_article') -> delete($aid);
		Db::name('PortalAddonarticle') -> delete($aid);
		Db::name('PortalAttachment') -> where('aid',$aid) -> delete();
		
		return $article['title'];
	}
	
}