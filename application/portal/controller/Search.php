<?php
namespace app\portal\controller;
use think\Db;
use app\portal\model\PortalArticle;
use app\common\controller\Common;

class Search extends Common
{
    public function index($tid=null,$search,$order='update_time desc'){
        $this -> _G['title'] = "搜索[{$search}]";
        $list = Db::name('portal_article') ->where('title','like',"%{$search}%") -> paginate(PAGE_NUM);
        $page = $list->render();
        $this -> _G['page'] = $page;
        
        $this -> _G['list'] = $list;
        
        return $this-> fetch('./portal/search');
        
    }

}