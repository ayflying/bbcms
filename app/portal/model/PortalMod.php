<?php
namespace app\portal\model;
use think\Model;

class PortalMod extends Model
{
    protected $name = 'portal_mod';
    protected $pk = 'id';
    //protected $insert = ['status'=>1];
    protected $autoWriteTimestamp = false;
    
	 //字段类型
	protected $type = [
        'data' => 'json',
    ];
    
    
    protected function scopeTable($query)
    {
        $query->where('id',2)->field('table');
    }
    
    protected function scopeThinkphp($query)
    {
        $query->where('id',2)->field('id,name');
    }
    
}

