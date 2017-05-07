<?php
namespace app\portal\controller;
use think\Db;
use think\Cache;
use app\portal\model\PortalArticle;
use app\common\controller\Common;


class Article extends Common{
	
	public function index($aid){
		
        if(empty(Cache::get('article_'.$aid))){
            $sql = PortalArticle::get($aid);
            $sql -> addonarticle;
            $sql -> attachment;
            //dump($sql -> toArray());
            if($sql['mod']>0){
                $mod = Db::name('portal_mod') -> cache(true) -> find($sql['mod']);
                $mod_list = Db::name('portal_mod_'.$sql['mod']) -> cache(true) -> find($aid);
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
                //$this -> assign('mod',$mod);
                $sql['mod'] = $mod;
            }
            Cache::set('article_'.$aid,$sql->toArray());
        }
        $sql = Cache::get('article_'.$aid);
        $this -> _G['menu'] = Db::name('portal_menu') -> cache('menu_'.$sql['tid']) -> find($sql['tid']);
        $this -> _G['article'] = $sql;
		//dump($this);
		
		return $this-> fetch($this -> _G['menu']['template2']);
	}

}