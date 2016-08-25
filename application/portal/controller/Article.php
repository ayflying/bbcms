<?php
namespace app\portal\controller;
use think\Db;

use app\common\controller\Common;
use app\portal\model\PortalArticle;

class Article extends Common{
	
	public function index($aid){
		
		$sql = PortalArticle::get($aid);
		$sql -> addonarticle;
		$sql -> attachment;
		//dump($sql -> toArray());
		
		if($sql['mod']>0){
			$mod = Db::name('portal_mod') -> find($sql['mod']);
			$mod_list = Db::name('portal_mod_'.$sql['mod']) -> find($aid);
			$mod = json_decode($mod['data'],true);
			foreach($mod as $key => $val){
				//dump($mod);
				$mod[$key] = [
					'name' => $val[0],
					'type' => $val[1],
					'param' => $val[2],
					'value' => $mod_list[$key],
				];
			}
			$this -> assign('mod',$mod);
		}
		
		
		//dump($mod);
		//$sql = \think\Db::name("portal_article") -> find($aid);
		$this -> assign('title',$sql['title'].' - ');
		$this -> assign('bb',$sql);
		
		
		return $this-> fetch('portal/article');
	}

}