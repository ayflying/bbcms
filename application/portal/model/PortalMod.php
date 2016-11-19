<?php
namespace app\portal\model;
use think\Model;

class PortalMenu extends Model
{
    protected $name = 'portal_mod';
    //protected $insert = ['status'=>1];
    protected $autoWriteTimestamp = false;
    
	 //字段类型
	protected $type = [
        'data' => 'json',
    ];
    
}

