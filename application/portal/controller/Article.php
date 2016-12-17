<?php
namespace app\portal\controller;
use think\Db;
use app\portal\model\PortalArticle;
use app\common\controller\Common;


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
		$this -> _G['menu'] = Db::name('portal_menu') -> find($sql['tid']);
        $this -> _G['article'] = $sql;
        $this -> assign('_G',$this -> _G);
		//dump($this);
		
		return $this-> fetch($this -> _G['menu']['template2']);
	}

}