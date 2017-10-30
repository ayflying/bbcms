<?php
namespace app\portal\model;
use think\Model;

class PortalArticle extends Model
{

	protected $name = 'portal_article';
    protected $pk = 'aid';
	protected $insert = ['status'=>1];
	protected $autoWriteTimestamp = true;

    //字段类型
	protected $type = [
        'thumb'    =>  'json',
    ];

	public function addonarticle(){
		//return $this->hasOne('PortalAddonarticle','aid')->field('aid,content');
		return $this->hasOne('PortalAddonarticle','aid');

	}
	public function attachment(){
		return $this->hasMany('PortalAttachment','aid');
	}
    public function menu(){
		//return $this->hasOne('PortalMenu','tid','tid');
        return $this->belongsTo('PortalMenu','tid');
	}

    static public function lists($tid){
        $list = $this -> where('tid',$tid) -> select();

        return $list;
    }

    static public function info(){
        $sql = $this -> where('aid',$aid) -> find();


        return $sql;
    }




}