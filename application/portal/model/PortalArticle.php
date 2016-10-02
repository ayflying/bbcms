<?php
namespace app\portal\model;
use think\Model;

class PortalArticle extends Model
{
	
	protected $name = 'portal_article';
	protected $insert = ['status'=>1];
	protected $autoWriteTimestamp = false;
	
	
	
	public function addonarticle(){
		return $this->hasOne('PortalAddonarticle','aid');
	}
	public function attachment(){
		return $this->hasMany('PortalAttachment','aid');
	}
    
    
    public function menu(){
		//return $this->hasOne('PortalMenu','tid','tid');
        return $this->belongsTo('PortalMenu','tid');
	}
    
    
    
	
	
	
}