<?php
namespace app\portal\controller;
use think\Db;
use think\facade\Cache;
use app\portal\model\PortalArticle;
use app\common\controller\Common;


class Article extends Common{

	public function index($aid){
        Db::name('portal_article') ->where('aid', $aid) ->setInc('click');
        if(empty(Cache::get('article_'.$aid))){
            //$sql = PortalArticle::get($aid);
            $sql = PortalArticle::where('aid',$aid) -> find();
            $sql -> addonarticle;
            $sql -> attachment;
            //dump($sql -> toArray());
            if($sql['mod']>0){
                $mod = Db::name('portal_mod') -> cache(true) -> where('id',$sql['mod']) -> find();
                $mod_list = Db::name('portal_mod_'.$sql['mod']) -> where('aid',$aid) -> cache(true) -> find();
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
        $this -> _G['menu'] = Db::name('portal_menu') -> cache('menu_'.$sql['tid']) -> where('tid',$sql['tid']) -> find();
        $this -> _G['user'] = Db::name('member_user') -> cache('user_'.$sql['uid']) -> where('uid',$sql['uid']) -> find();
        $this -> _G['article'] = $sql;
		//dump($this);
        
        
    
		return $this-> fetch($this -> _G['menu']['template_article']);
	}

}