<?php
namespace app\portal\model;
use think\Model;

class PortalMenu extends Model
{
    protected $name = 'portal_menu';
    protected $pk = 'tid';
    //protected $insert = ['status'=>1];
    protected $auto = ['template_article', 'template_list', 'template_add', 'template_edit'];
    protected $autoWriteTimestamp = false;
    
    /*
    protected function setTemplateArticleAttr($value)
    {
        if(empty($value)){
            return "./portal/article123";
        }else{
            return $value;
        }
        
    }
    */
    
    /*
    protected function setJumpAttr($value){
        return "sdsdgsdhjump";
        
    }
    */
    
    
    
}

