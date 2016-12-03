<?php
namespace app\portal\model;
use think\Model;

class PortalArticle extends Model
{
	
	protected $name = 'portal_article';
	protected $insert = ['status'=>1];
	protected $autoWriteTimestamp = true;
	
    //字段类型
	protected $type = [
        'thumb'    =>  'json',
    ];
	
	public function addonarticle(){
		return $this->hasOne('PortalAddonarticle','aid')->field('aid,content');
	}
	public function attachment(){
		return $this->hasMany('PortalAttachment','aid');
	}
    public function menu(){
		//return $this->hasOne('PortalMenu','tid','tid');
        return $this->belongsTo('PortalMenu','tid');
	}
    
    
    
	
	
	
}